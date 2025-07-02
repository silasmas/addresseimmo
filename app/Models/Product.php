<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * MANY-TO-MANY
     * Several users for several products
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * ONE-TO-MANY
     * One user for several products
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ONE-TO-MANY
     * One category for several products
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * MANY-TO-ONE
     * Several files for a product
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    /**
     * Get photo files
     */
    public function photos()
    {
        return $this->files()->where('file_type', 'photo');
    }

    /**
     * Get video files
     */
    public function videos()
    {
        return $this->files()->where('file_type', 'video');
    }

    /**
     * Get audio files
     */
    public function audios()
    {
        return $this->files()->where('file_type', 'audio');
    }

    /**
     * Get document files
     */
    public function documents()
    {
        return $this->files()->where('file_type', 'document');
    }

    /**
     * Convert product price to user currency
     */
    public function convertPrice($userCurrency)
    {
        // If the product currency and the user currency are the same, no conversion is required.
        if ($this->currency === $userCurrency) {
            return $this->price;
        }

        // Retrieve the conversion rate between the product currency and the user currency
        $conversionRate = getExchangeRate($this->currency, $userCurrency);

        // Calculate the converted price
        return round($this->price * $conversionRate, 2);
    }
}
