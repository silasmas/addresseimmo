<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Document justificatif d'une vente vérifiée.
 */
class VerifiedDocument extends Model
{
  protected $guarded = [];

  /**
   * Dossier parent.
   *
   * @return BelongsTo
   */
  public function verifiedSale(): BelongsTo
  {
    return $this->belongsTo(VerifiedSale::class);
  }
}
