<?php

declare(strict_types=1);

namespace App\Dto\Dish;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class UpdateDto extends Data
{
    public function __construct(
        private readonly int               $id,
        private readonly int               $userId,
        private readonly string|null       $title,
        private readonly string|null       $ingredients,
        private readonly string|null       $description,
        private readonly float|null        $price,
        private readonly UploadedFile|null $previewImage,
        private readonly UploadedFile|null $mainImage,
        private readonly array|null        $tagIds,
    )
    {
    }

    final public function getId(): int
    {
        return $this->id;
    }

    final public function getUserId(): int
    {
        return $this->userId;
    }

    final public function getTitle(): string|null
    {
        return $this->title;
    }

    final public function getIngredients(): string|null
    {
        return $this->ingredients;
    }

    final public function getDescription(): string|null
    {
        return $this->description;
    }

    final public function getPrice(): float|null
    {
        return $this->price;
    }

    final public function getPreviewImage(): UploadedFile|null
    {
        return $this->previewImage;
    }

    final public function getMainImage(): UploadedFile|null
    {
        return $this->mainImage;
    }

    final public function getTagIds(): array|null
    {
        return $this->tagIds;
    }
}
