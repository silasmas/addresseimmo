<?php

namespace App\Http\Middleware;

use App\Services\InstallationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirige vers l'installateur si l'application n'est pas encore déployée.
 */
class EnsureAppIsInstalled
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

    if ($request->is('install', 'install/*')) {
      return $next($request);
    }

    return redirect()->route('install.index');
  }
}
