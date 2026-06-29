<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseController;
use App\Models\Agency;
use Illuminate\Http\JsonResponse;

/**
 * API agences partenaires (Phase 2).
 */
class AgencyController extends BaseController
{
  /**
   * Liste les agences partenaires.
   *
   * @return JsonResponse
   */
  public function index(): JsonResponse
  {
    $agencies = Agency::orderBy('name')->get();

    return $this->handleResponse($agencies, 'Agences récupérées');
  }
}
