<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * ONE-TO-MANY
     * One cart for several payments
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Convert payment amount to user currency
     * 
     * @param  int  $userCurrency
     * @return float|int
     */
    public function convertAmount($userCurrency): float|int
    {
        // If the product currency and the user currency are the same, no conversion is required.
        if ($this->currency === $userCurrency) {
            return $this->amount;
        }

        // Retrieve the conversion rate between the product currency and the user currency
        $conversionRate = getExchangeRate($this->currency, $userCurrency);

        // Calculate the converted amount
        return round($this->amount * $conversionRate, 2);
    }
}
