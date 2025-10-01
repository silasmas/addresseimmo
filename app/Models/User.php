<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_connection' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * Accessor for Age.
     */
    public function age(): int
    {
        return Carbon::parse($this->attributes['birthdate'])->age;
    }

    /**
     * MANY-TO-MANY
     * Several roles for several users
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps()->withPivot('is_selected');
    }

    /**
     * MANY-TO-ONE
     * Several carts for a user
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'user_id');
    }

    /**
     * Selected role
     */
    public function getSelectedRoleAttribute()
    {
        return $this->roles()->wherePivot('is_selected', 1)->first();
    }

    /**
     * Unpaid cart
     */
    public function unpaidCart(): HasOne
    {
        return $this->hasOne(Cart::class)->where('is_paid', 0)->latest();
    }

    /**
     * Unpaid orders
     */
    public function unpaidOrders()
    {
        $orders = $this->unpaidCart()->with('customer_orders.product.photos')->first()->customer_orders ?? [];

        // Get user currency
        $userCurrency = $this->currency;

        // Apply currency conversion on each order
        foreach ($orders as $order) {
            $order->converted_price = $order->convertPriceAtThatTime($userCurrency);
        }

        return $orders;
    }

    /**
     * Unpaid cart total
     * 
     * @return float
     */
    public function unpaidCartTotal(): float
    {
        $unpaidCart = $this->unpaidCart()->with('customer_orders')->first();

        // Si le panier existe, calcule le total
        if ($unpaidCart) {
            $userCurrency = $this->currency;

            return $unpaidCart->customer_orders->sum(function ($order) use ($userCurrency) {
                return $order->subtotalPrice($userCurrency);
            });
        }

        return 0;
    }

    /**
     * Received feedbacks
     */
    public function receivedFeedbacks()
    {
        return $this->hasMany(CustomerFeedback::class, 'for_user_id');
    }

    /**
     * Get readable currency
     * 
     * @return float|null
     */
    public function getReadableCurrencyAttribute(): string|null
    {
        return !empty($this->currency) ? ($this->currency == 'USD' ? '$' : 'FC') : null;
    }

    /**
     * Average feedbacks rating
     * 
     * @return float|null
     */
    public function averageRating(): float|null
    {
        return $this->receivedFeedbacks()->avg('rating');
    }

    /**
     * Check if given product is in the user unpaid cart
     * 
     * @param  int  $productId
     * @return bool
     */
    public function hasProductInUnpaidCart($productId): bool
    {
        return Cart::where('user_id', $this->id)
                        ->where('is_paid', 0)
                        ->whereHas('customer_orders', function ($query) use ($productId) {
                            $query->where('product_id', $productId);
                        })->exists();
    }

    /**
     * Add product to cart
     */
    public function addProductToCart(int $productId, int $quantity = 1): CustomerOrder
    {
        return DB::transaction(function () use ($productId, $quantity) {
            // 1. Check or create a unpaid cart
            $cart = $this->carts()->where('is_paid', 0)->latest()->first();

            if (!$cart) {
                $cart = $this->carts()->create([
                    'is_paid' => 0,
                ]);
            }

            // 2. Get existing line (or NULL)
            $existingOrder = CustomerOrder::whereHas('cart', function ($q) {
                                                $q->where('user_id', $this->id)->where('is_paid', 0);
                                            })->where('product_id', $productId)->first();

            // 3. Get the product
            $product = Product::findOrFail($productId);

            // 4. Calculation of the total quantity requested (new + existing)
            $totalQuantity = $quantity + ($existingOrder?->quantity ?? 0);

            // 5. Stock verification
            // if ($product->quantity < $totalQuantity) {
            //     throw new \Exception("Stock insuffisant pour « {$product->product_name} ». (Disponible : {$product->quantity})");
            // }

            // 6. If the line already exists, we update its quantity
            if ($existingOrder) {
                $existingOrder->quantity = $totalQuantity;
                $existingOrder->save();

            } else {
                // 7. Create a new order line
                $cart = $this->carts()->where('is_paid', 0)->latest()->first();

                if (!$cart) {
                    $cart = $this->carts()->create(['is_paid' => 0]);
                }

                $existingOrder = $cart->customer_orders()->create([
                    'product_id'         => $product->id,
                    'quantity'           => $quantity,
                    'price_at_that_time' => $product->price,
                    'currency'           => $product->currency,
                ]);
            }

            // 8. Decrement the product quantity by the ordered quantity
            $product->decrement('quantity', $quantity);

            return $existingOrder;
        });
    }

    /**
     * Add product from session to cart
     */
    public function addProductsFromSessionToCart()
    {
        // Récupère les produits en session (panier)
        $cartItems = session()->get('cart', []);

        // Si des produits existent en session
        foreach ($cartItems as $productId => $item) {
            // Ajouter chaque produit au panier de l'utilisateur
            $this->addProductToCart($productId, $item['quantity']);
        }

        // Vider le panier en session après l'ajout
        session()->forget('cart');
    }

    /**
     * Update product quantity from the cart
     * 
     * @param  int $customerOrderId
     * @param  int $quantityChange
     * @param  string $action
     * @return bool
     */
    public function updateProductQuantityInCart($customerOrderId, $quantityChange, $action): bool
    {
        return DB::transaction(function () use ($customerOrderId, $quantityChange, $action) {
            // 1. Get the unpaid cart
            $cart = $this->carts()->where('is_paid', 0)->latest()->first();

            if (!$cart) {
                throw new \Exception('Panier non trouvé');
            }

            // 2. Find the order line by its ID
            $existingOrder = $cart->customer_orders()->where('id', $customerOrderId)->first();

            if (!$existingOrder) {
                throw new \Exception('Commande non trouvée');
            }

            // 3. Get the product related to the order line
            $product = $existingOrder->product;

            switch ($action) {
                case 'increment':
                    // Check if stock is sufficient for increment
                    if ($product->quantity <= 0) {
                        throw new \Exception("Stock insuffisant pour « {$product->product_name} ». (Disponible : {$product->quantity})");
                    }

                    // Increment quantity in cart
                    $existingOrder->increment('quantity', 1);

                    // Decrease stock
                    $product->decrement('quantity', 1);
                    break;

                case 'decrement':
                    // Check that the quantity in the cart is > 2
                    if ($existingOrder->quantity <= 1) {
                        throw new \Exception('La quantité doit être au moins 1 offre commandée');
                    }

                    // Decrease quantity in cart
                    $existingOrder->decrement('quantity', 1);

                    // Increment the stock
                    $product->increment('quantity', 1);
                    break;

                case 'update':
                    // Check the new quantity
                    if ($quantityChange < 1) {
                        throw new \Exception('La quantité doit être au moins 1 offre commandée');
                    }

                    // Update quantity in cart
                    $existingOrder->update(['quantity' => $quantityChange]);

                    // Adjust stock according to the new quantity
                    $stockDifference = $quantityChange - $existingOrder->quantity;

                    if ($stockDifference > 0) {
                        // If we increase the order quantity, check that there is enough product stock
                        if ($product->quantity < $stockDifference) {
                            throw new \Exception("Stock insuffisant pour « {$product->product_name} ». (Disponible : {$product->quantity})");
                        }

                        // Decrease product stock based on increase
                        $product->decrement('quantity', $stockDifference);

                    } else {
                        // If we decrease the order quantity, increase the product stock
                        $product->increment('quantity', abs($stockDifference));
                    }
                    break;
                default:
                    throw new \Exception('Quelle action voulez-vous faire ?');
            }

            return true; // Indicate that the operation was successful
        });
    }

    /**
     * Remove product from cart
     * 
     * @param  int $customerOrderId
     * @return bool
     */
    public function removeProductFromCart($customerOrderId): bool
    {
        return DB::transaction(function () use ($customerOrderId) {
            // 1. Get the unpaid cart
            $cart = $this->carts()->where('is_paid', 0)->latest()->first();

            if (!$cart) {
                throw new \Exception('Panier non trouvé');
            }

            // 2. Find the order line by its ID
            $existingOrder = $cart->customer_orders()->where('id', $customerOrderId)->first();

            if (!$existingOrder) {
                throw new \Exception('Commande non trouvée');
            }

            // 3. Get the product related to the order line
            $product = $existingOrder->product;

            // 4. Delete the order line from the cart
            $existingOrder->delete();

            // 5. Increment the stock of the product
            $product->increment('quantity', $existingOrder->quantity);

            return true; // Indicate success
        });
    }
}
