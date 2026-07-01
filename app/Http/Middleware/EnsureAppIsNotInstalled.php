<?php

namespace App\Http\Middleware;

use App\Services\InstallationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Empêche l'accès à l'installateur une fois l'application déployée.
 */
class EnsureAppIsNotInstalled
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
    if (!$this->installationService->isInstalled()) {
      return $next($request);
    }

    return redirect('/admin');
  }
}
