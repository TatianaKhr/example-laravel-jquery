<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DishImageResource extends JsonResource
{
    final public function toArray($request): array
    {
        return [
            'dish_id' => $this->dish_id,
            'path' => $this->path,
            'type_id' => $this->type_id,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
