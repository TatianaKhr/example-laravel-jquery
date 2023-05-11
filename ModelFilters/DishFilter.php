<?php

declare(strict_types=1);

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class DishFilter extends ModelFilter
{
    public $relations = [];

    final public function keyword(string $keyword): self
    {
        return $this->where(function ($query) use ($keyword) {
            return $query->where('title', 'LIKE', "%$keyword%")
                ->orWhere('description', 'LIKE', "%$keyword%");
        });
    }

    final public function price(string $price): self
    {
        [$min, $max] = explode(',', $price);
        $max = number_format((float)$max, 2);
        $min = number_format((float)$min, 2);

        return $this->where('price', '>=', $min)
            ->where('price', '<=', $max)
            ->with('dishImages', 'tags');
    }

    final public function tagsId(string $tagsId): self
    {
        $filter = function ($subQuery) use ($tagsId) {
            $subQuery->where('tag_id', $tagsId);
        };

        return $this->whereHas('tags', $filter);
    }

    public function userId(int $userId)
    {
        return $this->where('user_id', $userId);
    }
}
