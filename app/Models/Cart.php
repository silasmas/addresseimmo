<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * ONE-TO-MANY
     * One user for several customer_orders
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * MANY-TO-ONE
     * Several customer_orders for a cart
     */
    public function customer_orders(): HasMany
    {
        return $this->hasMany(CustomerOrder::class);
    }

    /**
     * MANY-TO-ONE
     * Several payments for a cart
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Total price of ordered products
     *
     * @return float
     */
    public function totalAmount(): float
    {
        return round($this->customer_orders->sum('price_at_that_time'), 2);
    }

    /**
     * Total price of ordered products, converted to user currency
     *
     * @return float
     */
    public function totalConvertedAmount($userCurrency): float
    {
        return $this->customer_orders->sum(function ($order) use ($userCurrency) {
            // Si la devise de l'ordre est la même que celle de l'utilisateur
            if ($order->currency == $userCurrency) {
                return round($order->price_at_that_time, 2);
            }

            // Si les devises sont différentes, on effectue la conversion
            $conversionRate = getExchangeRate($order->currency, $userCurrency);

            // Retourner le prix converti
            return round($order->price_at_that_time * $conversionRate, 2);
        });
    }
}
