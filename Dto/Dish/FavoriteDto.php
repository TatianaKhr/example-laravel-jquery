<?php

declare(strict_types=1);

namespace App\Dto\Dish;

use Spatie\LaravelData\Data;

class FavoriteDto extends Data
{
    public function __construct(
        private readonly int $userId,
        private readonly int $dishId,
    )
    {
    }

    final public function getUserId(): int
    {
        return $this->userId;
    }

    final public function getDishId(): int
    {
        return $this->dishId;
    }
}
