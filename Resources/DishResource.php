<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Http\Resources\DishImageResource;
use App\Http\Resources\LikesResource;
use App\Http\Resources\TagResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DishResource extends JsonResource
{
    final public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'ingredients' => $this->ingredients,
            'description' => $this->description,
            'price' => $this->price,
            'likes_count' => $this->likes_count,
            'dish_images' => DishImageResource::collection($this->whenLoaded('dishImages')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'likes' => LikesResource::collection($this->whenLoaded('likes')),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
