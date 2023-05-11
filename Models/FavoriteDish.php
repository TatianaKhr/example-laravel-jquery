<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Dish;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FavoriteDish extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'favorite_dish';

    protected $guarded = false;

    protected $fillable = [
        'user_id',
        'dish_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dishes(): BelongsToMany
    {
        return $this->belongsToMany(Dish::class, 'favorite_dish');
    }

    public function scopeGetByDishId($query, int $id): Builder
    {
        return $query->where('dish_id', '=', $id);
    }

    public function scopeGetByUserId($query, int $id): Builder
    {
        return $query->where('user_id', '=', $id);
    }
}
