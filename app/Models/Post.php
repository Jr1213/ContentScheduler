<?php

namespace App\Models;

use App\Enums\PostStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'image_url',
        'scheduled_time',
        'status'
    ];

    protected $casts = [
        'status' => PostStatusEnum::class,
        'scheduled_time' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function platforms(): BelongsToMany
    {
        return  $this->belongsToMany(Platform::class, PostPlatform::class, 'post_id', 'platform_id');
    }

    public function postPlatform():HasMany
    {
        return $this->hasMany(PostPlatform::class);
    }
    //mocking publish 
    public function publish(int $platform_id): bool
    {
        return rand(0, 1) ? true : false;
    }
}
