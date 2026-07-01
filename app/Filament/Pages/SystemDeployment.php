<?php

namespace App\Filament\Pages;

use App\Services\InstallationService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use UnitEnum;

/**
 * Page back-office de déploiement et mise à jour système.
 */
class SystemDeployment extends Page
{
  protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-server-stack';

  protected static ?string $navigationLabel = 'Déploiement';

  protected static ?string $title = 'Déploiement & mises à jour';

  protected static string|UnitEnum|null $navigationGroup = 'Administration';

  protected static ?int $navigationSort = 99;

  protected string $view = 'filament.pages.system-deployment';

  /** @var array<string, mixed> */
  public array $status = [];

  /**
   * Charge le statut système au montage de la page.
   *
   * @param InstallationService $installationService Service d'installation
   */
  public function mount(InstallationService $installationService): void
  {
    $this->refreshStatus($installationService);
  }

  /**
   * Restreint l'accès aux administrateurs.
   *
   * @return bool
   */
  public static function canAccess(): bool
  {
    $user = auth()->user();

    return $user !== null && $user->isSuperAdmin();
  }

  /**
   * Actions disponibles dans l'en-tête de page.
   *
   * @return array<int, Action>
   */
  protected function getHeaderActions(): array
  {
    return [
      Action::make('refresh')
        ->label('Actualiser')
        ->icon('heroicon-o-arrow-path')
        ->action(fn (InstallationService $installationService) => $this->refreshStatus($installationService)),
    ];
  }

  /**
   * Exécute les migrations Laravel.
   *
   * @param InstallationService $installationService Service d'installation
   */
  public function runMigrations(InstallationService $installationService): void
  {
    try {
      $installationService->runMigrations();
      $this->refreshStatus($installationService);

      Notification::make()
        ->title('Migrations exécutées')
        ->success()
        ->send();
    } catch (\Throwable $exception) {
      Notification::make()
        ->title('Erreur migrations')
        ->body($exception->getMessage())
        ->danger()
        ->send();
    }
  }

  /**
   * Synchronise les migrations avec une base importée.
   *
   * @param InstallationService $installationService Service d'installation
   */
  public function runMigrationSync(InstallationService $installationService): void
  {
    try {
      $installationService->runMigrationSync();
      $this->refreshStatus($installationService);

      Notification::make()
        ->title('Synchronisation migrations terminée')
        ->success()
        ->send();
    } catch (\Throwable $exception) {
      Notification::make()
        ->title('Erreur synchronisation')
        ->body($exception->getMessage())
        ->danger()
        ->send();
    }
  }

  /**
   * Exécute les seeders de base.
   *
   * @param InstallationService $installationService Service d'installation
   */
  public function runBaseSeeders(InstallationService $installationService): void
  {
    try {
      $installationService->runSeeders(false);
      $this->refreshStatus($installationService);

      Notification::make()
        ->title('Seeders de base exécutés')
        ->success()
        ->send();
    } catch (\Throwable $exception) {
      Notification::make()
        ->title('Erreur seeders')
        ->body($exception->getMessage())
        ->danger()
        ->send();
    }
  }

  /**
   * Exécute les seeders de démonstration.
   *
   * @param InstallationService $installationService Service d'installation
   */
  public function runDemoSeeders(InstallationService $installationService): void
  {
    try {
      $installationService->runSeeders(true);
      $this->refreshStatus($installationService);

      Notification::make()
        ->title('Données démo chargées')
        ->success()
        ->send();
    } catch (\Throwable $exception) {
      Notification::make()
        ->title('Erreur seeders démo')
        ->body($exception->getMessage())
        ->danger()
        ->send();
    }
  }

  /**
   * Vide les caches applicatifs.
   *
   * @param InstallationService $installationService Service d'installation
   */
  public function clearCaches(InstallationService $installationService): void
  {
    try {
      $installationService->clearCaches();
      $this->refreshStatus($installationService);

      Notification::make()
        ->title('Caches vidés')
        ->success()
        ->send();
    } catch (\Throwable $exception) {
      Notification::make()
        ->title('Erreur cache')
        ->body($exception->getMessage())
        ->danger()
        ->send();
    }
  }

  /**
   * Recharge le statut affiché dans la page.
   *
   * @param InstallationService $installationService Service d'installation
   */
  private function refreshStatus(InstallationService $installationService): void
  {
    $this->status = $installationService->getDetailedStatus();
  }
}
