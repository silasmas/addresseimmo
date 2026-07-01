<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseController;
use App\Services\InstallationService;
use Illuminate\Http\JsonResponse;

/**
 * Expose le statut d'installation pour le frontend Next.js.
 */
class InstallController extends BaseController
{
  /**
   * @param InstallationService $installationService Service d'installation
   */
  public function __construct(private readonly InstallationService $installationService)
  {
  }

  /**
   * Retourne l'état du déploiement backend.
   *
   * @return JsonResponse
   */
  public function status(): JsonResponse
  {
    return $this->handleResponse(
      $this->installationService->getPublicStatus(),
      'Statut installation AddressImmo'
    );
  }
}
