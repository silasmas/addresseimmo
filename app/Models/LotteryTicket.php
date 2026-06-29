<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Ticket de loto immobilier.
 */
class LotteryTicket extends Model
{
  protected $guarded = [];

  /**
   * Tirage associé.
   *
   * @return BelongsTo
   */
  public function lotteryDraw(): BelongsTo
  {
    return $this->belongsTo(LotteryDraw::class);
  }

  /**
   * Acheteur du ticket.
   *
   * @return BelongsTo
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}
