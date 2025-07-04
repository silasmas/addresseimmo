<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class CustomerOrder extends Model
{
    use HasFactory;

    protected $table = 'customer_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * ONE-TO-MANY
     * One cart for several customer_orders
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * ONE-TO-MANY
     * One product for several customer_orders
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Convert order price to user currency
     * 
     * @return float
     */
    public function convertPriceAtThatTime($userCurrency): float
    {
        // If the order currency and the user currency are the same, no conversion is required.
        if ($this->currency === $userCurrency) {
            return $this->price_at_that_time;
        }

        // Retrieve the conversion rate between the order currency and the user currency
        $conversionRate = getExchangeRate($this->currency, $userCurrency);

        // Calculate the converted price
        return round($this->price_at_that_time * $conversionRate, 2);
    }

    /**
     * Subtotal
     * 
     * @param  string  $userCurrency
     * @return float
     */
    public function subtotalPrice($userCurrency): float
    {
        // On utilise la méthode de conversion de devise
        return $this->convertPriceAtThatTime($userCurrency) * $this->quantity;
    }
}
