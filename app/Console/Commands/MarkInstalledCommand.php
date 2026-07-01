<?php

namespace App\Console\Commands;

use App\Services\InstallationService;
use Illuminate\Console\Command;

/**
 * Marque l'application comme installée sans relancer l'assistant web.
 */
class MarkInstalledCommand extends Command
{
  /**
   * Signature Artisan.
   *
   * @var string
   */
  protected $signature = 'app:mark-installed {--email= : Email admin de référence}';

  /**
   * Description de la commande.
   *
   * @var string
   */
  protected $description = 'Marque AddressImmo comme installée (déploiements existants)';

  /**
   * Exécute la commande.
   *
   * @param InstallationService $installationService Service d'installation
   * @return int Code de sortie
   */
  public function handle(InstallationService $installationService): int
  {
    $installationService->markAsInstalled([
      'admin_email' => $this->option('email'),
      'marked_by' => 'cli',
    ]);

    $this->info('Application marquée comme installée.');

    return self::SUCCESS;
  }
}
