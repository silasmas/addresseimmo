<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class Session extends Model
{
    use HasFactory;

    protected $table = 'sessions';
	protected $primaryKey = 'id';
	public $incrementing = false;
	protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * ONE-TO-MANY
     * One user for several sessions
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
