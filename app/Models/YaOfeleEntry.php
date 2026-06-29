<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Participation gratuite à Ya Ofele.
 */
class YaOfeleEntry extends Model
{
  protected $guarded = [];

  /**
   * Tirage associé.
   *
   * @return BelongsTo
   */
  public function yaOfeleDraw(): BelongsTo
  {
    return $this->belongsTo(YaOfeleDraw::class);
  }

  /**
   * Utilisateur inscrit.
   *
   * @return BelongsTo
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}
