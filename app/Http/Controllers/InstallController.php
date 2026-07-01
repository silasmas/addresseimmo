<?php

namespace App\Http\Controllers;

use App\Services\InstallationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Interface web d'installation initiale AddressImmo.
 */
class InstallController extends Controller
{
  /**
   * @param InstallationService $installationService Service d'installation
   */
  public function __construct(private readonly InstallationService $installationService)
  {
  }

  /**
   * Affiche l'assistant d'installation.
   *
   * @return View
   */
  public function index(): View
  {
    return view('install.index', [
      'status' => $this->installationService->getDetailedStatus(),
    ]);
  }

  /**
   * Exécute les migrations depuis l'interface web.
   *
   * @param Request $request Requête HTTP
   * @return RedirectResponse|JsonResponse
   */
  public function migrate(Request $request): RedirectResponse|JsonResponse
  {
    try {
      $mode = $request->input('mode', 'migrate');
      $result = $mode === 'sync'
        ? $this->installationService->runMigrationSync()
        : $this->installationService->runMigrations();

      $message = 'Migrations exécutées avec succès.';

      if ($request->expectsJson()) {
        return response()->json([
          'success' => true,
          'message' => $message,
          'data' => $result,
        ]);
      }

      return back()->with('success', $message);
    } catch (\Throwable $exception) {
      $message = 'Erreur migrations : ' . $exception->getMessage();

      if ($request->expectsJson()) {
        return response()->json(['success' => false, 'message' => $message], 422);
      }

      return back()->with('error', $message);
    }
  }

  /**
   * Exécute les seeders depuis l'interface web.
   *
   * @param Request $request Requête HTTP
   * @return RedirectResponse|JsonResponse
   */
  public function seed(Request $request): RedirectResponse|JsonResponse
  {
    try {
      $includeDemo = $request->boolean('include_demo');
      $result = $this->installationService->runSeeders($includeDemo);
      $message = 'Seeders exécutés : ' . implode(', ', $result['seeders']) . '.';

      if ($request->expectsJson()) {
        return response()->json([
          'success' => true,
          'message' => $message,
          'data' => $result,
        ]);
      }

      return back()->with('success', $message);
    } catch (\Throwable $exception) {
      $message = 'Erreur seeders : ' . $exception->getMessage();

      if ($request->expectsJson()) {
        return response()->json(['success' => false, 'message' => $message], 422);
      }

      return back()->with('error', $message);
    }
  }

  /**
   * Crée le super administrateur depuis l'interface web.
   *
   * @param Request $request Requête HTTP
   * @return RedirectResponse|JsonResponse
   */
  public function createAdmin(Request $request): RedirectResponse|JsonResponse
  {
    $validated = $request->validate([
      'firstname' => ['required', 'string', 'max:255'],
      'lastname' => ['nullable', 'string', 'max:255'],
      'email' => ['required', 'email', 'max:255', 'unique:users,email'],
      'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone'],
      'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    try {
      $user = $this->installationService->createSuperAdmin($validated);
      $message = "Super administrateur créé : {$user->email}.";

      if ($request->expectsJson()) {
        return response()->json([
          'success' => true,
          'message' => $message,
          'data' => ['email' => $user->email],
        ]);
      }

      return back()->with('success', $message);
    } catch (\Throwable $exception) {
      $message = 'Erreur création admin : ' . $exception->getMessage();

      if ($request->expectsJson()) {
        return response()->json(['success' => false, 'message' => $message], 422);
      }

      return back()->with('error', $message);
    }
  }

  /**
   * Finalise l'installation et redirige vers le back-office.
   *
   * @param Request $request Requête HTTP
   * @return RedirectResponse|JsonResponse
   */
  public function finish(Request $request): RedirectResponse|JsonResponse
  {
    if (!$this->installationService->isDatabaseConnected()) {
      $message = 'Connexion base de données requise.';

      return $request->expectsJson()
        ? response()->json(['success' => false, 'message' => $message], 422)
        : back()->with('error', $message);
    }

    if (count($this->installationService->getPendingMigrations()) > 0) {
      $message = 'Exécutez d\'abord les migrations.';

      return $request->expectsJson()
        ? response()->json(['success' => false, 'message' => $message], 422)
        : back()->with('error', $message);
    }

    if (!$this->installationService->hasSuperAdmin()) {
      $message = 'Créez d\'abord un super administrateur.';

      return $request->expectsJson()
        ? response()->json(['success' => false, 'message' => $message], 422)
        : back()->with('error', $message);
    }

    $meta = [
      'admin_email' => $request->input('admin_email'),
    ];

    $this->installationService->markAsInstalled($meta);
    $this->installationService->clearCaches();

    $message = 'Installation terminée. Vous pouvez accéder au back-office.';

    if ($request->expectsJson()) {
      return response()->json([
        'success' => true,
        'message' => $message,
        'data' => $this->installationService->getPublicStatus(),
      ]);
    }

    return redirect('/' . trim(config('install.admin_panel_path', 'back-office'), '/'))->with('success', $message);
  }
}
