<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\API\ProductController as LegacyProductController;
use App\Http\Requests\Api\V1\AddCartItemRequest;
use App\Http\Requests\Api\V1\PurchaseCartRequest;
use App\Http\Requests\Api\V1\UpdateCartItemRequest;
use App\Http\Resources\Cart as CartResource;
use App\Models\CustomerOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Gère le panier utilisateur via l'API v1.
 */
class CartController extends BaseController
{
  /**
   * Retourne le panier non payé de l'utilisateur connecté.
   *
   * @param Request $request Requête HTTP
   * @return JsonResponse
   */
  public function show(Request $request): JsonResponse
  {
    $cart = $request->user()->unpaidCart()->with('customer_orders.product.photos')->first();

    if ($cart === null) {
      return $this->handleResponse(null, __('notifications.find_cart_404'));
    }

    return $this->handleResponse(
      new CartResource($cart),
      __('notifications.find_cart_success')
    );
  }

  /**
   * Ajoute une offre au panier.
   *
   * @param AddCartItemRequest $request Données validées
   * @return JsonResponse
   */
  public function store(AddCartItemRequest $request): JsonResponse
  {
    try {
      $request->user()->addProductToCart(
        (int) $request->product_id,
        (int) ($request->quantity ?? 1)
      );

      $cart = $request->user()->unpaidCart()->with('customer_orders.product.photos')->first();

      return $this->handleResponse(
        new CartResource($cart),
        __('notifications.create_cart_success')
      );
    } catch (\Exception $exception) {
      return $this->handleError($exception->getMessage(), [], 422);
    }
  }

  /**
   * Met à jour la quantité d'une ligne de panier.
   *
   * @param UpdateCartItemRequest $request Données validées
   * @param int $orderId Identifiant de la ligne commande
   * @return JsonResponse
   */
  public function update(UpdateCartItemRequest $request, int $orderId): JsonResponse
  {
    $order = $this->findUserOrder($request, $orderId);

    if ($order === null) {
      return $this->handleError(__('notifications.find_order_404'));
    }

    try {
      $quantity = (int) ($request->quantity ?? 1);
      $request->user()->updateProductQuantityInCart($orderId, $quantity, $request->action);

      $cart = $request->user()->unpaidCart()->with('customer_orders.product.photos')->first();

      return $this->handleResponse(
        new CartResource($cart),
        __('notifications.update_cart_success')
      );
    } catch (\Exception $exception) {
      return $this->handleError($exception->getMessage(), [], 422);
    }
  }

  /**
   * Supprime une ligne du panier.
   *
   * @param Request $request Requête HTTP
   * @param int $orderId Identifiant de la ligne commande
   * @return JsonResponse
   */
  public function destroy(Request $request, int $orderId): JsonResponse
  {
    $order = $this->findUserOrder($request, $orderId);

    if ($order === null) {
      return $this->handleError(__('notifications.find_order_404'));
    }

    try {
      $request->user()->removeProductFromCart($orderId);

      $cart = $request->user()->unpaidCart()->with('customer_orders.product.photos')->first();

      return $this->handleResponse(
        $cart ? new CartResource($cart) : null,
        __('notifications.delete_cart_success')
      );
    } catch (\Exception $exception) {
      return $this->handleError($exception->getMessage(), [], 422);
    }
  }

  /**
   * Initie un paiement FlexPay pour le panier courant.
   *
   * @param PurchaseCartRequest $request Données validées
   * @return JsonResponse
   */
  public function purchase(PurchaseCartRequest $request): JsonResponse
  {
    $user = $request->user();
    $cart = $user->unpaidCart()->first();

    if ($cart === null) {
      return $this->handleError(__('notifications.find_cart_404'));
    }

    $purchaseRequest = Request::create('/', 'POST', array_merge($request->validated(), [
      'cart_id' => $cart->id,
      'user_id' => $user->id,
    ]));

    return app(LegacyProductController::class)->purchase($purchaseRequest, $cart->id, $user->id);
  }

  /**
   * Retrouve une ligne de panier appartenant à l'utilisateur connecté.
   *
   * @param Request $request Requête HTTP
   * @param int $orderId Identifiant commande
   * @return CustomerOrder|null
   */
  private function findUserOrder(Request $request, int $orderId): ?CustomerOrder
  {
    $cart = $request->user()->unpaidCart()->first();

    if ($cart === null) {
      return null;
    }

    return $cart->customer_orders()->where('id', $orderId)->first();
  }
}
