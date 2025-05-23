<?php

namespace App\Models;

use App\PlatformStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostPlatform extends Model
{
    /** @use HasFactory<\Database\Factories\PostPlatformFactory> */
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'post_id',
        'platform_id',
        'platfrom_status'
    ];

    protected $casts = [
        'platform_status' => PlatformStatusEnum::class
    ];

    public function post():BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function platform():BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }
}
