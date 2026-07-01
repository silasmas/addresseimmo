<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\AddressImmoSeeder;
use Database\Seeders\DemoDataSeeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

/**
 * Gère l'état d'installation, les migrations, seeders et le super administrateur.
 */
class InstallationService
{
  /**
   * Indique si l'application est marquée comme installée.
   *
   * @return bool True si installée
   */
  public function isInstalled(): bool
  {
    if (filter_var(env('APP_INSTALLED', false), FILTER_VALIDATE_BOOLEAN)) {
      return true;
    }

    if (File::exists(config('install.installed_file'))) {
      return true;
    }

    return $this->isLegacyDeploymentReady();
  }

  /**
   * Retourne le statut public pour le frontend et l'API.
   *
   * @return array<string, mixed> Statut synthétique
   */
  public function getPublicStatus(): array
  {
    $databaseConnected = $this->isDatabaseConnected();
    $pendingMigrations = $databaseConnected ? count($this->getPendingMigrations()) : 0;
    $hasAdmin = $databaseConnected && $this->hasSuperAdmin();
    $installed = $this->isInstalled();
    $requirementsOk = $this->requirementsAreMet()['ok'];

    return [
      'installed' => $installed,
      'requirements_ok' => $requirementsOk,
      'database_connected' => $databaseConnected,
      'migrations_pending' => $pendingMigrations,
      'has_admin' => $hasAdmin,
      'frontend_ready' => $installed && $databaseConnected && $pendingMigrations === 0 && $hasAdmin,
      'version' => config('install.version'),
      'install_url' => url('/install'),
      'admin_url' => url('/admin'),
    ];
  }

  /**
   * Retourne un statut détaillé pour le back-office.
   *
   * @return array<string, mixed> Détails techniques
   */
  public function getDetailedStatus(): array
  {
    $public = $this->getPublicStatus();
    $requirements = $this->requirementsAreMet();
    $installedMeta = $this->getInstalledMeta();

    return array_merge($public, [
      'requirements' => $requirements,
      'pending_migrations' => $this->getPendingMigrations(),
      'installed_at' => $installedMeta['installed_at'] ?? null,
      'admin_email' => $installedMeta['admin_email'] ?? null,
      'php_version' => PHP_VERSION,
      'app_env' => config('app.env'),
    ]);
  }

  /**
   * Vérifie les prérequis serveur.
   *
   * @return array{ok: bool, checks: array<int, array<string, mixed>>}
   */
  public function requirementsAreMet(): array
  {
    $checks = [];

    $checks[] = [
      'label' => 'PHP >= 8.2',
      'ok' => version_compare(PHP_VERSION, '8.2.0', '>='),
      'value' => PHP_VERSION,
    ];

    $checks[] = [
      'label' => 'Clé APP_KEY',
      'ok' => !empty(config('app.key')),
      'value' => !empty(config('app.key')) ? 'Définie' : 'Manquante',
    ];

    foreach (config('install.required_extensions') as $extension) {
      $checks[] = [
        'label' => "Extension {$extension}",
        'ok' => extension_loaded($extension),
        'value' => extension_loaded($extension) ? 'OK' : 'Manquante',
      ];
    }

    foreach (config('install.writable_paths') as $path) {
      $checks[] = [
        'label' => "Écriture : {$path}",
        'ok' => is_dir($path) && is_writable($path),
        'value' => is_writable($path) ? 'OK' : 'Non inscriptible',
      ];
    }

    $checks[] = [
      'label' => 'Connexion base de données',
      'ok' => $this->isDatabaseConnected(),
      'value' => $this->isDatabaseConnected() ? 'OK' : 'Échec',
    ];

    return [
      'ok' => collect($checks)->every(fn (array $check) => $check['ok']),
      'checks' => $checks,
    ];
  }

  /**
   * Teste la connexion à la base de données.
   *
   * @return bool True si connectée
   */
  public function isDatabaseConnected(): bool
  {
    try {
      DB::connection()->getPdo();

      return true;
    } catch (\Throwable) {
      return false;
    }
  }

  /**
   * Liste les migrations en attente.
   *
   * @return array<int, string> Noms de fichiers migration
   */
  public function getPendingMigrations(): array
  {
    if (!$this->isDatabaseConnected()) {
      return [];
    }

    try {
      if (!Schema::hasTable('migrations')) {
        return $this->listMigrationFiles();
      }

      $migrator = app('migrator');
      $files = $migrator->getMigrationFiles(database_path('migrations'));
      $ran = $migrator->getRepository()->getRan();

      return array_values(array_diff(array_keys($files), $ran));
    } catch (\Throwable) {
      return [];
    }
  }

  /**
   * Exécute les migrations Laravel.
   *
   * @return array{output: string, pending_before: int}
   */
  public function runMigrations(): array
  {
    $pendingBefore = count($this->getPendingMigrations());
    Artisan::call('migrate', ['--force' => true]);

    return [
      'output' => trim(Artisan::output()),
      'pending_before' => $pendingBefore,
    ];
  }

  /**
   * Synchronise les migrations avec une base importée.
   *
   * @return array{output: string}
   */
  public function runMigrationSync(): array
  {
    Artisan::call('migrate:sync', ['--force' => true]);

    return [
      'output' => trim(Artisan::output()),
    ];
  }

  /**
   * Exécute les seeders de base et optionnellement la démo.
   *
   * @param bool $includeDemo Inclure DemoDataSeeder
   * @return array{seeders: array<int, string>}
   */
  public function runSeeders(bool $includeDemo = false): array
  {
    $seeders = ['AddressImmoSeeder'];
    (new AddressImmoSeeder())->run();

    if ($includeDemo) {
      $seeders[] = 'DemoDataSeeder';
      (new DemoDataSeeder())->run();
    }

    return ['seeders' => $seeders];
  }

  /**
   * Crée le super administrateur Filament.
   *
   * @param array<string, mixed> $data Données admin
   * @return User Utilisateur créé
   */
  public function createSuperAdmin(array $data): User
  {
    (new AddressImmoSeeder())->run();

    $adminRole = Role::firstOrCreate(
      ['role_name' => config('install.admin_role')],
      ['role_description' => 'Responsable de la gestion du fonctionnement de la plateforme.']
    );

    $user = User::create([
      'firstname' => $data['firstname'],
      'lastname' => $data['lastname'] ?? null,
      'email' => $data['email'],
      'phone' => $data['phone'] ?? null,
      'password' => Hash::make($data['password']),
      'status' => 'activated',
      'currency' => $data['currency'] ?? 'USD',
    ]);

    $user->roles()->syncWithoutDetaching([
      $adminRole->id => ['is_selected' => 1],
    ]);

    DB::table('role_user')
      ->where('user_id', $user->id)
      ->where('role_id', '!=', $adminRole->id)
      ->update(['is_selected' => 0]);

    DB::table('role_user')
      ->where('user_id', $user->id)
      ->where('role_id', $adminRole->id)
      ->update(['is_selected' => 1]);

    return $user->fresh(['roles']);
  }

  /**
   * Vérifie qu'un super administrateur existe.
   *
   * @return bool True si au moins un admin existe
   */
  public function hasSuperAdmin(): bool
  {
    if (!$this->isDatabaseConnected() || !Schema::hasTable('users') || !Schema::hasTable('roles')) {
      return false;
    }

    $adminRole = Role::where('role_name', config('install.admin_role'))->first();

    if ($adminRole === null) {
      return false;
    }

    return DB::table('role_user')->where('role_id', $adminRole->id)->exists();
  }

  /**
   * Marque l'application comme installée.
   *
   * @param array<string, mixed> $meta Métadonnées d'installation
   */
  public function markAsInstalled(array $meta = []): void
  {
    $payload = array_merge([
      'installed_at' => now()->toIso8601String(),
      'version' => config('install.version'),
    ], $meta);

    File::ensureDirectoryExists(dirname(config('install.installed_file')));
    File::put(
      config('install.installed_file'),
      json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );

    $this->updateEnvInstalledFlag(true);
  }

  /**
   * Vide les caches applicatifs.
   *
   * @return array{commands: array<int, string>}
   */
  public function clearCaches(): array
  {
    $commands = [
      'config:clear',
      'route:clear',
      'view:clear',
      'cache:clear',
    ];

    foreach ($commands as $command) {
      Artisan::call($command);
    }

    return ['commands' => $commands];
  }

  /**
   * Lit les métadonnées du fichier installed.json.
   *
   * @return array<string, mixed>
   */
  public function getInstalledMeta(): array
  {
    if (!File::exists(config('install.installed_file'))) {
      return [];
    }

    return json_decode(File::get(config('install.installed_file')), true) ?? [];
  }

  /**
   * Liste tous les fichiers de migration disponibles.
   *
   * @return array<int, string>
   */
  private function listMigrationFiles(): array
  {
    $migrator = app('migrator');

    return array_keys($migrator->getMigrationFiles(database_path('migrations')));
  }

  /**
   * Détecte un déploiement déjà opérationnel avant le fichier installed.json.
   *
   * @return bool True si migrations OK et super admin présent
   */
  private function isLegacyDeploymentReady(): bool
  {
    try {
      return $this->isDatabaseConnected()
        && count($this->getPendingMigrations()) === 0
        && $this->hasSuperAdmin();
    } catch (\Throwable) {
      return false;
    }
  }

  /**
   * Met à jour APP_INSTALLED dans le fichier .env si possible.
   *
   * @param bool $installed État d'installation
   */
  private function updateEnvInstalledFlag(bool $installed): void
  {
    $envPath = base_path('.env');

    if (!File::exists($envPath) || !is_writable($envPath)) {
      return;
    }

    $contents = File::get($envPath);
    $value = $installed ? 'true' : 'false';
    $line = "APP_INSTALLED={$value}";

    if (str_contains($contents, 'APP_INSTALLED=')) {
      $contents = preg_replace('/^APP_INSTALLED=.*/m', $line, $contents);
    } else {
      $contents .= PHP_EOL . $line . PHP_EOL;
    }

    File::put($envPath, $contents);
  }
}
