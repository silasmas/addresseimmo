<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseController;
use App\Models\LotteryDraw;
use Illuminate\Http\JsonResponse;

/**
 * API loto immobilier (Phase 2).
 */
class LotteryController extends BaseController
{
  /**
   * Liste les tirages loto actifs.
   *
   * @return JsonResponse
   */
  public function index(): JsonResponse
  {
    $draws = LotteryDraw::with('product.photos')->orderByDesc('created_at')->get();

    return $this->handleResponse($draws, 'Tirages loto récupérés');
  }

  /**
   * Détail d'un tirage loto.
   *
   * @param int $id Identifiant tirage
   * @return JsonResponse
   */
  public function show(int $id): JsonResponse
  {
    $draw = LotteryDraw::with(['product.photos', 'tickets'])->find($id);

    if ($draw === null) {
      return $this->handleError('Tirage non trouvé');
    }

    return $this->handleResponse($draw, 'Tirage récupéré');
  }
}
