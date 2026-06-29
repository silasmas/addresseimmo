<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Vérifie que l'utilisateur connecté possède au moins un des rôles requis.
 */
class EnsureUserHasRole
{
  /**
   * Traite la requête entrante.
   *
   * @param Request $request Requête HTTP
   * @param Closure $next Suite du pipeline
   * @param string ...$roles Noms de rôles autorisés
   * @return Response
   */
  public function handle(Request $request, Closure $next, string ...$roles): Response
  {
    $user = $request->user();

    if ($user === null) {
      return redirect()->route('login');
    }

    if (!$user->hasAnyRole($roles)) {
      abort(403, 'Accès refusé.');
    }

    return $next($request);
  }
}
