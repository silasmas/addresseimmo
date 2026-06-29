<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseController;
use App\Models\YaOfeleDraw;
use Illuminate\Http\JsonResponse;

/**
 * API Ya Ofele Gratos (Phase 2).
 */
class YaOfeleController extends BaseController
{
  /**
   * Liste les tirages Ya Ofele ouverts.
   *
   * @return JsonResponse
   */
  public function index(): JsonResponse
  {
    $draws = YaOfeleDraw::orderByDesc('created_at')->get();

    return $this->handleResponse($draws, 'Tirages Ya Ofele récupérés');
  }

  /**
   * Détail d'un tirage Ya Ofele.
   *
   * @param int $id Identifiant tirage
   * @return JsonResponse
   */
  public function show(int $id): JsonResponse
  {
    $draw = YaOfeleDraw::with('entries')->find($id);

    if ($draw === null) {
      return $this->handleError('Tirage non trouvé');
    }

    return $this->handleResponse($draw, 'Tirage récupéré');
  }
}
