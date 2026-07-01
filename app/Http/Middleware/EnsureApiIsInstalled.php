<?php

namespace App\Http\Middleware;

use App\Services\InstallationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Bloque l'API métier tant que le déploiement backend n'est pas terminé.
 */
class EnsureApiIsInstalled
{
  /**
   * @param InstallationService $installationService Service d'installation
   */
  public function __construct(private readonly InstallationService $installationService)
  {
  }

  /**
   * Traite la requête entrante.
   *
   * @param Request $request Requête HTTP
   * @param Closure $next Suite du pipeline
   * @return Response
   */
  public function handle(Request $request, Closure $next): Response
  {
    if ($this->installationService->isInstalled()) {
      return $next($request);
    }

    $status = $this->installationService->getPublicStatus();

    return response()->json([
      'success' => false,
      'message' => 'Le backend AddressImmo n\'est pas encore installé.',
      'data' => $status,
    ], 503);
  }
}
