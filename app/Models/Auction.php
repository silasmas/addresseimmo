<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Enchère immobilière.
 */
class Auction extends Model
{
  protected $guarded = [];

  protected $casts = [
    'starts_at' => 'datetime',
    'ends_at' => 'datetime',
  ];

  /**
   * Bien associé à l'enchère.
   *
   * @return BelongsTo
   */
  public function product(): BelongsTo
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * Offres reçues pour cette enchère.
   *
   * @return HasMany
   */
  public function bids(): HasMany
  {
    return $this->hasMany(AuctionBid::class);
  }
}
