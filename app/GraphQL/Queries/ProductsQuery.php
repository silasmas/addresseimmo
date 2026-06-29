<?php

namespace App\GraphQL\Queries;

use App\Models\Product;

/**
 * Résolveur GraphQL pour le catalogue produits.
 */
class ProductsQuery
{
  /**
   * Retourne les annonces filtrées.
   *
   * @param mixed $root Valeur racine
   * @param array<string, mixed> $args Arguments GraphQL
   * @return \Illuminate\Database\Eloquent\Collection<int, Product>
   */
  public function __invoke($root, array $args)
  {
    $query = Product::query()
      ->with(['category', 'photos'])
      ->where(function ($builder) {
        $builder->where('quantity', '>', 0)
          ->orWhere('is_service', 1);
      });

    if (!empty($args['action'])) {
      $query->where('action', $args['action']);
    }

    if (!empty($args['type'])) {
      $query->where('type', $args['type']);
    }

    if (!empty($args['category_id'])) {
      $query->where('category_id', $args['category_id']);
    }

    if (!empty($args['city'])) {
      $query->where('city', 'like', '%' . $args['city'] . '%');
    }

    if (!empty($args['search'])) {
      $search = $args['search'];
      $query->where(function ($builder) use ($search) {
        $builder->where('product_name', 'like', '%' . $search . '%')
          ->orWhere('product_description', 'like', '%' . $search . '%');
      });
    }

    $perPage = min((int) ($args['first'] ?? 12), 50);
    $page = (int) ($args['page'] ?? 1);

    return $query->orderByDesc('created_at')
      ->skip(($page - 1) * $perPage)
      ->take($perPage)
      ->get();
  }
}
