<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseController;
use App\Models\VerifiedSale;
use Illuminate\Http\JsonResponse;

/**
 * API ventes vérifiées (Phase 2).
 */
class VerifiedSaleController extends BaseController
{
  /**
   * Liste les ventes vérifiées publiées.
   *
   * @return JsonResponse
   */
  public function index(): JsonResponse
  {
    $sales = VerifiedSale::with(['product.photos', 'documents'])
      ->where('status', 'verified')
      ->orderByDesc('verified_at')
      ->get();

    return $this->handleResponse($sales, 'Ventes vérifiées récupérées');
  }

  /**
   * Détail d'une vente vérifiée.
   *
   * @param int $id Identifiant dossier
   * @return JsonResponse
   */
  public function show(int $id): JsonResponse
  {
    $sale = VerifiedSale::with(['product.photos', 'documents', 'user'])->find($id);

    if ($sale === null) {
      return $this->handleError('Vente vérifiée non trouvée');
    }

    return $this->handleResponse($sale, 'Vente vérifiée récupérée');
  }
}
