<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Tirage Ya Ofele Gratos.
 */
class YaOfeleDraw extends Model
{
  protected $guarded = [];

  protected $casts = [
    'draw_at' => 'datetime',
  ];

  /**
   * Participations enregistrées.
   *
   * @return HasMany
   */
  public function entries(): HasMany
  {
    return $this->hasMany(YaOfeleEntry::class);
  }
}
