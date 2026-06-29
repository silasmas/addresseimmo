<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Tirage du loto immobilier.
 */
class LotteryDraw extends Model
{
  protected $guarded = [];

  protected $casts = [
    'draw_at' => 'datetime',
  ];

  /**
   * Bien mis en jeu.
   *
   * @return BelongsTo
   */
  public function product(): BelongsTo
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * Tickets vendus pour ce tirage.
   *
   * @return HasMany
   */
  public function tickets(): HasMany
  {
    return $this->hasMany(LotteryTicket::class);
  }
}
