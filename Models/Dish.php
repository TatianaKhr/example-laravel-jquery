<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\Likeable;
use App\Enums\LikeableTypeId;
use App\Models\Concerns\Likes;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dish extends Model implements Likeable
{
    use HasFactory;
    use SoftDeletes;
    use Likes;
    use Filterable;

    protected $table = 'dishes';

    protected $guarded = false;

    final public function dishImages(): HasMany
    {
        return $this->hasMany(\App\Models\DishImage::class, 'dish_id', 'id');
    }

    final public function tags(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Tag::class, 'dishes_tags', 'dish_id', 'tag_id');
    }

    final public function favorites(): HasMany
    {
        return $this->hasMany(\App\Models\FavoriteDish::class, 'dish_id', 'id');
    }

    protected static function boot(): void
    {
        parent::boot();
        static::deleted(function ($fileToDelete) {
            $fileToDelete->dishImages()->delete();
            $fileToDelete->tags()->detach();
            $fileToDelete->favorites()->delete();
        });
    }

    public function scopeGetLikedByUserId($query, int $id): Builder
    {
        return $query->whereHas('likes', function ($subQuery) use ($id) {
            $subQuery->getByLikeableType(LikeableTypeId::Dish->value)->getByUserId($id);
        });
    }

    public function scopeGetFavoritedByUserId($query, int $id): Builder
    {
        return $query->whereHas('favorites', function ($subQuery) use ($id) {
            $subQuery->getByUserId($id);
        });
    }

    public function scopeWithDishImagesAndTagsAndLikes($query): Builder
    {
        return $query->with('dishImages', 'tags', 'likes');
    }
}
