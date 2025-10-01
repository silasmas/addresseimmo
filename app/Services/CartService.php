<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class CartService
{
    /**
     * Convert the price of an ordered product into the session currency
     */
    public function convertPriceAtThatTime($price, $productCurrency, $userCurrency): float
    {
        if ($productCurrency === $userCurrency) {
            return $price;
        }

        $conversionRate = getExchangeRate($productCurrency, $userCurrency);

        return round($price * $conversionRate, 3);  // Return the converted price
    }

    /**
     * Calculate the subtotal of a product in the cart with currency conversion
     */
    public function subtotalPrice($item, $userCurrency): float
    {
        if ($userCurrency == $item['currency']) {
            return $item['price'] * $item['quantity'];  // Normal price multiplied by quantity
        }

        $convertedPrice = $this->convertPriceAtThatTime($item['price'], $item['currency'], $userCurrency);

        return $convertedPrice * $item['quantity'];  // Converted price multiplied by quantity
    }

    /**
     * Get total price from cart in the session
     */
    public function getCartTotalFromSession(): float
    {
        // Get session currency
        $currency = Session::get('currency', 'USD');  // 'USD' by default

        // Get cart from the session
        $cart = Session::get('cart', []);
        $total = 0;

        // Browse each product in the cart
        foreach ($cart as $item) {
            // If the product currency is different from the session currency, we convert
            if ($item['currency'] !== $currency) {
                $exchangeRate = getExchangeRate($item['currency'], $currency);
                $convertedPrice = $item['price'] * $exchangeRate;

            // Otherwise, no need to convert
            } else {
                $convertedPrice = $item['price'];
            }

            // Add converted "price * quantity" to total
            $total += $convertedPrice * $item['quantity'];
        }

        return $total;
    }
}
