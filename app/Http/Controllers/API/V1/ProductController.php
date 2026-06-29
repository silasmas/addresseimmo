<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\Product as ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Catalogue public des annonces pour le frontend Next.js.
 */
class ProductController extends BaseController
{
  /**
   * Liste paginée des annonces avec filtres.
   *
   * @param Request $request Requête HTTP
   * @return JsonResponse
   */
  public function index(Request $request): JsonResponse
  {
    $query = Product::query()
      ->with(['category', 'user', 'photos'])
      ->where(function ($builder) {
        $builder->where('quantity', '>', 0)
          ->orWhere('is_service', 1);
      });

    if ($request->filled('action')) {
      $query->where('action', $request->action);
    }

    if ($request->filled('type')) {
      $query->where('type', $request->type);
    }

    if ($request->filled('category_id')) {
      $query->where('category_id', $request->category_id);
    }

    if ($request->filled('city')) {
      $query->where('city', 'like', '%' . $request->city . '%');
    }

    if ($request->filled('search')) {
      $search = $request->search;
      $query->where(function ($builder) use ($search) {
        $builder->where('product_name', 'like', '%' . $search . '%')
          ->orWhere('product_description', 'like', '%' . $search . '%')
          ->orWhere('municipality', 'like', '%' . $search . '%')
          ->orWhere('neighborhood', 'like', '%' . $search . '%');
      });
    }

    $perPage = min((int) $request->input('per_page', 12), 50);
    $products = $query->orderByDesc('created_at')->paginate($perPage);

    return $this->handleResponse([
      'items' => ProductResource::collection($products->items()),
      'pagination' => [
        'current_page' => $products->currentPage(),
        'last_page' => $products->lastPage(),
        'per_page' => $products->perPage(),
        'total' => $products->total(),
      ],
    ], __('notifications.find_all_products_success'));
  }

  /**
   * Affiche le détail d'une annonce.
   *
   * @param int $id Identifiant produit
   * @return JsonResponse
   */
  public function show(int $id): JsonResponse
  {
    $product = Product::with([
      'category',
      'user',
      'photos',
      'videos',
      'documents',
      'receivedFeedbacks',
    ])->find($id);

    if ($product === null) {
      return $this->handleError(__('notifications.find_product_404'));
    }

    return $this->handleResponse(
      new ProductResource($product),
      __('notifications.find_product_success')
    );
  }
}
