<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Dish;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

class DishImage extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = false;

    protected $table = 'dish_images';

    protected $fillable = [
        'dish_id',
        'path',
        'type_id',
    ];

    public function dishes(): BelongsTo
    {
        return $this->belongsTo(Dish::class, 'dish_id', 'id');
    }

    public function scopeGetByDishId($query, int $dishId): Builder
    {
        return $query->where('dish_id', '=', $dishId);
    }

    public function scopeGetByTypeId($query, int $typeId): Builder
    {
        return $query->where('type_id', '=', $typeId);
    }
}
