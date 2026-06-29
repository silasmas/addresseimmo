<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseController;
use App\Models\Auction;
use Illuminate\Http\JsonResponse;

/**
 * API enchères immobilières (Phase 2).
 */
class AuctionController extends BaseController
{
  /**
   * Liste les enchères publiées.
   *
   * @return JsonResponse
   */
  public function index(): JsonResponse
  {
    $auctions = Auction::with('product.photos')->orderByDesc('created_at')->get();

    return $this->handleResponse($auctions, 'Enchères récupérées');
  }

  /**
   * Détail d'une enchère.
   *
   * @param int $id Identifiant enchère
   * @return JsonResponse
   */
  public function show(int $id): JsonResponse
  {
    $auction = Auction::with(['product.photos', 'bids'])->find($id);

    if ($auction === null) {
      return $this->handleError('Enchère non trouvée');
    }

    return $this->handleResponse($auction, 'Enchère récupérée');
  }
}
