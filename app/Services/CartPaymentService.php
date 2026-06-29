<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

/**
 * Centralise la logique de confirmation de paiement des paniers.
 */
class CartPaymentService
{
  /**
   * Statut FlexPay : paiement réussi.
   */
  public const STATUS_SUCCESS = 0;

  /**
   * Statut FlexPay : paiement en attente.
   */
  public const STATUS_PENDING = 1;

  /**
   * Statut FlexPay : paiement échoué.
   */
  public const STATUS_FAILED = 2;

  /**
   * Marque un panier comme payé après confirmation FlexPay.
   *
   * @param Cart $cart Panier à confirmer
   * @param Payment|null $payment Paiement associé
   * @return Cart
   */
  public function markCartAsPaid(Cart $cart, ?Payment $payment = null): Cart
  {
    return DB::transaction(function () use ($cart, $payment) {
      if ($cart->is_paid) {
        return $cart;
      }

      $paymentCode = $cart->payment_code;

      if ($paymentCode === null) {
        $paymentCode = 'STRT-' . random_int(1000000, 9999999) . '-' . date('Y.m.d');
      }

      $cart->update([
        'payment_code' => $paymentCode,
        'is_paid' => 1,
      ]);

      if ($payment !== null) {
        $payment->update(['status' => self::STATUS_SUCCESS]);
      }

      return $cart->fresh();
    });
  }

  /**
   * Associe un code de paiement au panier sans le marquer payé.
   *
   * @param Cart $cart Panier en cours
   * @return Cart
   */
  public function assignPendingPaymentCode(Cart $cart): Cart
  {
    if ($cart->payment_code !== null) {
      return $cart;
    }

    $cart->update([
      'payment_code' => 'STRT-' . random_int(1000000, 9999999) . '-' . date('Y.m.d'),
      'is_paid' => 0,
    ]);

    return $cart->fresh();
  }
}
