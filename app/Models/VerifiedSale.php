<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Dossier de vente vérifiée.
 */
class VerifiedSale extends Model
{
  protected $guarded = [];

  protected $casts = [
    'verified_at' => 'datetime',
  ];

  /**
   * Annonce concernée.
   *
   * @return BelongsTo
   */
  public function product(): BelongsTo
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * Vendeur demandeur.
   *
   * @return BelongsTo
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Documents du dossier.
   *
   * @return HasMany
   */
  public function documents(): HasMany
  {
    return $this->hasMany(VerifiedDocument::class);
  }
}
