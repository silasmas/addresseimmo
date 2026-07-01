<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\InstallationService;
use Illuminate\Console\Command;

/**
 * Promouvoit un utilisateur existant en super administrateur Filament.
 */
class PromoteSuperAdminCommand extends Command
{
  /**
   * Signature Artisan.
   *
   * @var string
   */
  protected $signature = 'app:promote-super-admin {email : Email de l\'utilisateur}';

  /**
   * Description de la commande.
   *
   * @var string
   */
  protected $description = 'Attribue le rôle Administrateur (super admin) à un utilisateur existant';

  /**
   * Exécute la commande.
   *
   * @param InstallationService $installationService Service d'installation
   * @return int Code de sortie
   */
  public function handle(InstallationService $installationService): int
  {
    $email = $this->argument('email');
    $user = User::where('email', $email)->first();

    if ($user === null) {
      $this->error("Utilisateur introuvable : {$email}");

      return self::FAILURE;
    }

    $installationService->assignSuperAdminRole($user);
    $installationService->clearCaches();

    $panelPath = trim(config('install.admin_panel_path', 'back-office'), '/');
    $this->info("Super admin activé pour {$user->email}");
    $this->line("Back-office : " . url("/{$panelPath}"));

    return self::SUCCESS;
  }
}
