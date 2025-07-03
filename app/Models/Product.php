<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

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
     * MANY-TO-ONE
     * Several customer_orders for a product
     */
    public function customer_orders(): HasMany
    {
        return $this->hasMany(CustomerOrder::class);
    }

    /**
     * Get photo files
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos(): HasMany
    {
        return $this->hasMany(File::class)->where('file_type', 'photo');
    }

    public function getPhotosList(): Collection
    {
        return $this->photos;
    }

    /**
     * Get video files
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function videos(): HasMany
    {
        return $this->hasMany(File::class)->where('file_type', 'video');
    }

    public function getVideosList(): Collection
    {
        return $this->videos;
    }

    /**
     * Get audio files
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function audios(): HasMany
    {
        return $this->hasMany(File::class)->where('file_type', 'audio');
    }

    public function getAudiosList(): Collection
    {
        return $this->audios;
    }

    /**
     * Get document files
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents(): HasMany
    {
        return $this->hasMany(File::class)->where('file_type', 'document');
    }

    public function getDocumentsList(): Collection
    {
        return $this->documents;
    }

    /**
     * Convert product price to user currency
     * 
     * @param  int  $userCurrency
     * @return float|int
     */
    public function convertPrice($userCurrency): float|int
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

    /**
     * Most recent products
     * 
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function mostRecent($limit = 10): Collection
    {
        return self::orderBy('created_at', 'desc')->take($limit)->get();
    }

    /**
     * Most ordered products
     * 
     * @param  int  $limit
     * @param  string  $period
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function mostOrdered($limit = 10, $period = null)
    {
        $startDate = match ($period) {
            'daily'      => Carbon::now()->startOfDay(),
            'weekly'     => Carbon::now()->subDays(7),
            'monthly'    => Carbon::now()->subDays(30),
            'half-yearly'=> Carbon::now()->subMonths(6),
            'yearly'     => Carbon::now()->subYear(),
            default      => null,
        };

        return self::select('products.*')
                        ->withSum(['customer_orders as total_quantity_ordered' => function ($q) use ($startDate) {
                            $q->join('carts', 'customer_orders.cart_id', '=', 'carts.id');
                            // ->where('carts.is_paid', 1);

                            if ($startDate) {
                                $q->where('customer_orders.created_at', '>=', $startDate);
                            }

                        }], 'quantity')
                        ->withSum(['customer_orders as total_revenue' => function ($q) use ($startDate) {
                            $q->join('carts', 'customer_orders.cart_id', '=', 'carts.id')
                            ->where('carts.is_paid', 1);

                            if ($startDate) {
                                $q->where('customer_orders.created_at', '>=', $startDate);
                            }

                        }], DB::raw('price_at_that_time * quantity'))
                        ->orderByDesc('total_quantity_ordered')
                        ->take($limit)->get();
    }

    /**
     * Search products with filter
     * 
     * USAGE :
     * ======
     * $filters = [
     *     'category_id' => 2,
     *     'user_id' => 5,
     *     'type' => 'product',
     *     'action' => 'sell',
     * ];
     * 
     * $results = Product::searchWithFilters($filters);
     * 
     * @param  string  $data
     * @param  array  $filters
     * @param  int  $perPage
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function searchWithFilters($data, array $filters, $perPage = 15)
    {
        return self::query()
                    ->where('quantity', '>', 0)
                    ->when(isset($data), fn($q) => $q->where('product_name', 'LIKE', '%' . $data))
                    ->when(isset($filters['category_id']), fn($q) => $q->where('category_id', $filters['category_id']))
                    ->when(isset($filters['user_id']), fn($q) => $q->where('user_id', $filters['user_id']))
                    ->when(isset($filters['type']), fn($q) => $q->where('type', $filters['type']))
                    ->when(isset($filters['action']), fn($q) => $q->where('action', $filters['action']))
                    ->orderBy('price', 'asc')
                    ->paginate($perPage);
    }

    /**
     * Received feedbacks
     */
    public function receivedFeedbacks()
    {
        return $this->hasMany(CustomerFeedback::class, 'for_product_id');
    }

    /**
     * Average feedbacks rating
     */
    public function averageRating()
    {
        return $this->receivedFeedbacks()->avg('rating');
    }
}
