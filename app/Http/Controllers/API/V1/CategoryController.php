<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\Cart as CartResource;
use App\Http\Resources\Category as CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Expose les catégories d'annonces via l'API v1.
 */
class CategoryController extends BaseController
{
  /**
   * Liste les catégories avec filtre optionnel services/biens.
   *
   * @param Request $request Requête HTTP
   * @return JsonResponse
   */
  public function index(Request $request): JsonResponse
  {
    $query = Category::query()->orderBy('category_name');

    if ($request->filled('for_service')) {
      $query->where('for_service', (int) $request->for_service);
    }

    $categories = $query->get();

    return $this->handleResponse(
      CategoryResource::collection($categories),
      __('notifications.find_all_categories_success')
    );
  }
}
