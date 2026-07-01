<?php

namespace App\Console\Commands;

use App\Services\InstallationService;
use Illuminate\Console\Command;

/**
 * Installe AddressImmo en ligne de commande (CI/CD ou serveur).
 */
class AppInstallCommand extends Command
{
  /**
   * Signature Artisan.
   *
   * @var string
   */
  protected $signature = 'app:install
                            {--migrate : Exécuter les migrations}
                            {--sync : Synchroniser une base importée}
                            {--seed : Exécuter les seeders de base}
                            {--demo : Exécuter aussi DemoDataSeeder}
                            {--firstname=Admin : Prénom super admin}
                            {--lastname= : Nom super admin}
                            {--email= : Email super admin}
                            {--password= : Mot de passe super admin}
                            {--force : Forcer même si déjà installé}';

  /**
   * Description de la commande.
   *
   * @var string
   */
  protected $description = 'Installe AddressImmo (migrations, seeders, super admin)';

  /**
   * Exécute la commande.
   *
   * @param InstallationService $installationService Service d'installation
   * @return int Code de sortie
   */
  public function handle(InstallationService $installationService): int
  {
    if ($installationService->isInstalled() && !$this->option('force')) {
      $this->warn('Application déjà installée. Utilisez --force pour continuer.');

      return self::SUCCESS;
    }

    if ($this->option('sync')) {
      $this->info('Synchronisation migrations...');
      $installationService->runMigrationSync();
      $this->line('OK');
    } elseif ($this->option('migrate')) {
      $this->info('Exécution migrations...');
      $installationService->runMigrations();
      $this->line('OK');
    }

    if ($this->option('seed') || $this->option('demo')) {
      $this->info('Exécution seeders...');
      $installationService->runSeeders($this->option('demo'));
      $this->line('OK');
    }

    if ($this->option('email') && $this->option('password')) {
      if (!$installationService->hasSuperAdmin()) {
        $this->info('Création super administrateur...');
        $user = $installationService->createSuperAdmin([
          'firstname' => $this->option('firstname'),
          'lastname' => $this->option('lastname'),
          'email' => $this->option('email'),
          'password' => $this->option('password'),
          'password_confirmation' => $this->option('password'),
        ]);
        $this->line("Admin créé : {$user->email}");
      } else {
        $this->warn('Un super administrateur existe déjà.');
      }
    }

    if (
      count($installationService->getPendingMigrations()) === 0
      && $installationService->hasSuperAdmin()
    ) {
      $installationService->markAsInstalled([
        'admin_email' => $this->option('email'),
        'marked_by' => 'cli',
      ]);
      $installationService->clearCaches();
      $this->info('Installation terminée.');
    } else {
      $this->warn('Installation incomplète : migrations ou super admin manquant.');
    }

    return self::SUCCESS;
  }
}
