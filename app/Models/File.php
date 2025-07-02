<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class File extends Model
{
    use HasFactory;

    protected $table = 'files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * ONE-TO-MANY
     * One product for several files
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * ONE-TO-MANY
     * One post for several files
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
