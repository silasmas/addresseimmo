<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Offre (bid) sur une enchère.
 */
class AuctionBid extends Model
{
  protected $guarded = [];

  /**
   * Enchère parente.
   *
   * @return BelongsTo
   */
  public function auction(): BelongsTo
  {
    return $this->belongsTo(Auction::class);
  }

  /**
   * Utilisateur ayant enchéri.
   *
   * @return BelongsTo
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}
