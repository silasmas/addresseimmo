<?php

namespace App\Console\Commands;

use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Synchronise les migrations avec une base déjà importée (SQL dump).
 *
 * Marque comme exécutées les migrations dont la table existe déjà,
 * puis exécute uniquement les migrations manquantes.
 */
#[AsCommand(name: 'migrate:sync')]
class SyncMigrationsCommand extends BaseCommand
{
  /**
   * Signature de la commande Artisan.
   *
   * @var string
   */
  protected $signature = 'migrate:sync
                            {--database= : Connexion BDD à utiliser}
                            {--force : Exécuter sans confirmation}';

  /**
   * Description affichée dans php artisan list.
   *
   * @var string
   */
  protected $description = 'Synchronise les migrations avec une base existante (import SQL)';

  /**
   * Instance du gestionnaire de migrations Laravel.
   *
   * @var Migrator
   */
  protected Migrator $migrator;

  /**
   * Correspondance migration → table principale.
   *
   * @var array<string, string>
   */
  protected array $tableMap = [
    '2019_08_19_000000_create_failed_jobs_table' => 'failed_jobs',
    '2025_06_10_000001_create_users_table' => 'users',
    '2025_06_10_000002_create_roles_table' => 'roles',
    '2025_06_10_000003_create_role_user_table' => 'role_user',
    '2025_06_10_000004_create_categories_table' => 'categories',
    '2025_06_10_000005_create_products_table' => 'products',
    '2025_06_10_000006_create_product_user_table' => 'product_user',
    '2025_06_10_000007_create_files_table' => 'files',
    '2025_06_10_000008_create_carts_table' => 'carts',
    '2025_06_10_000009_create_customer_orders_table' => 'customer_orders',
    '2025_06_10_000010_create_payments_table' => 'payments',
    '2025_06_10_000011_create_customer_feedbacks_table' => 'customer_feedbacks',
    '2025_06_10_000012_create_password_resets_table' => 'password_resets',
    '2025_06_10_000013_create_personal_access_tokens_table' => 'personal_access_tokens',
    '2025_06_10_000014_create_sessions_table' => 'sessions',
    '2025_06_10_000015_create_category_product_table' => 'category_product',
  ];

  /**
   * Initialise la commande avec le migrator Laravel.
   *
   * @param Migrator $migrator Gestionnaire de migrations
   */
  public function __construct(Migrator $migrator)
  {
    parent::__construct();

    $this->migrator = $migrator;
  }

  /**
   * Point d'entrée de la commande.
   *
   * @return int Code de sortie (0 = succès)
   */
  public function handle(): int
  {
    return $this->migrator->usingConnection($this->option('database'), function () {
      $repository = $this->migrator->getRepository();

      if (!$repository->repositoryExists()) {
        $repository->createRepository();
        $this->info('Table `migrations` créée.');
      }

      $pending = $this->getPendingMigrationNames();

      if (count($pending) === 0) {
        $this->info('Aucune migration en attente — base déjà synchronisée.');
        return self::SUCCESS;
      }

      $database = config('database.connections.' . config('database.default') . '.database');
      $toMark = [];
      $toRun = [];

      foreach ($pending as $migration) {
        $table = $this->tableMap[$migration] ?? null;

        if ($table !== null && Schema::hasTable($table)) {
          $toMark[] = ['migration' => $migration, 'table' => $table];
        } else {
          $toRun[] = $migration;
        }
      }

      $this->newLine();
      $this->info("Base cible : {$database}");
      $this->info('Migrations en attente : ' . count($pending));
      $this->info('À marquer (table existante) : ' . count($toMark));
      $this->info('À exécuter (table absente) : ' . count($toRun));
      $this->newLine();

      if (count($toMark) > 0) {
        $this->line('<fg=yellow>Tables déjà présentes :</>');
        foreach ($toMark as $item) {
          $this->line("  • {$item['migration']} → `{$item['table']}`");
        }
        $this->newLine();
      }

      if (count($toRun) > 0) {
        $this->line('<fg=cyan>Tables à créer :</>');
        foreach ($toRun as $migration) {
          $table = $this->tableMap[$migration] ?? '?';
          $this->line("  • {$migration} → `{$table}`");
        }
        $this->newLine();
      }

      if (!$this->option('force') && !$this->confirm('Continuer la synchronisation ?', true)) {
        $this->warn('Synchronisation annulée.');
        return self::SUCCESS;
      }

      $batch = (int) DB::table('migrations')->max('batch') + 1;

      foreach ($toMark as $item) {
        $repository->log($item['migration'], $batch);
        $this->line("<fg=green>✓ Marquée</> {$item['migration']}");
      }

      if (count($toRun) > 0) {
        $files = $this->migrator->getMigrationFiles($this->getMigrationPaths());
        $paths = [];

        foreach ($toRun as $migration) {
          if (isset($files[$migration])) {
            $paths[] = $files[$migration];
          }
        }

        if (count($paths) > 0) {
          $this->newLine();
          $this->info('Exécution des migrations manquantes…');
          $this->migrator->setOutput($this->output)->run($paths);
        }
      }

      $this->newLine();
      $this->info('Synchronisation terminée.');
      $this->line('Vérifiez avec : <fg=cyan>php artisan migrate:status</>');

      return self::SUCCESS;
    });
  }

  /**
   * Retourne les noms de migrations non encore enregistrées.
   *
   * @return array<int, string>
   */
  protected function getPendingMigrationNames(): array
  {
    $ran = $this->migrator->getRepository()->getRan();
    $files = $this->migrator->getMigrationFiles($this->getMigrationPaths());
    $pending = [];

    foreach ($files as $file) {
      $name = $this->migrator->getMigrationName($file);

      if (!in_array($name, $ran, true)) {
        $pending[] = $name;
      }
    }

    return $pending;
  }
}
