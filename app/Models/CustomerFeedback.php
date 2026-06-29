<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class CustomerFeedback extends Model
{
    use HasFactory;

    protected $table = 'customer_feedbacks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * ONE-TO-MANY
     * One user for several customer_feedbacks
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Alias Filament pour l'auteur de l'avis.
     *
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Alias Filament pour l'utilisateur cible de l'avis.
     *
     * @return BelongsTo
     */
    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'for_user_id');
    }

    /**
     * Alias Filament pour le produit concerné par l'avis.
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'for_product_id');
    }
}
