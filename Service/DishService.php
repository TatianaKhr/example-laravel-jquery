<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Dish\FilterDto;
use App\Dto\Dish\StoreDto;
use App\Dto\Dish\UpdateDto;
use App\Dto\DishImage\Dto;
use App\Enums\DishImagesType;
use App\Models\Dish;
use App\Models\DishImage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DishService
{
    public function __construct(
        private readonly Dish      $dish,
        private readonly Storage   $storage,
        private readonly DishImage $dishImage,
    )
    {
    }

    final public function list(FilterDto $dto): LengthAwarePaginator
    {
        return $this->dish->filter($dto->getData())->with('dishImages', 'tags', 'likes')->withCount('likes')->paginate(4);
    }

    final public function show(int $id): Dish
    {
        return $this->dish->with('dishImages', 'tags')->find(id: $id);
    }

    final public function store(StoreDto $dto): Dish
    {
        return DB::transaction(function () use ($dto) {
            $dish = $this->dish->create([
                'user_id' => $dto->getUserId(),
                'title' => $dto->getTitle(),
                'ingredients' => $dto->getIngredients(),
                'description' => $dto->getDescription(),
                'price' => $dto->getPrice(),
            ]);

            $images = [
                DishImagesType::Main->value => $dto->getMainImage(),
                DishImagesType::Preview->value => $dto->getPreviewImage(),
            ];

            foreach ($images as $key => $value) {
                $image = new Dto(
                    dishId: $dish->id,
                    typeId: $key,
                    path: (string)$this->storage::disk('public')->put('/images', $value)
                );

                $this->createImages(dto: $image, dish: $dish);
            }

            if (!is_null($dto->getTagIds())) {
                $tagIds = $dto->getTagIds();
                $dish->tags()->sync($tagIds);
            }

            return $dish;
        });
    }

    final public function update(UpdateDto $dto, int $id): Dish
    {
        return DB::transaction(function () use ($id, $dto) {
            $dish = $this->show(id: $id);
            $images = [];

            if (!is_null($dto->getTagIds())) {
                $tagIds = $dto->getTagIds();
                $dish->tags()->sync($tagIds);
            }

            if (!is_null($dto->getMainImage())) {
                $images[DishImagesType::Main->value] = $dto->getMainImage();
            }
            if (!is_null($dto->getPreviewImage())) {
                $images[DishImagesType::Preview->value] = $dto->getPreviewImage();
            }

            foreach ($images as $key => $value) {
                $dishImage = new Dto(
                    dishId: $dish->id,
                    typeId: $key,
                    path: (string)$this->storage::disk('public')->put('/images', $value)
                );

                $this->updateImage(dto: $dishImage, dish: $dish);
            }

            $dish->fill([
                'title' => $dto->getTitle(),
                'ingredients' => $dto->getIngredients(),
                'description' => $dto->getDescription(),
                'price' => $dto->getPrice(),
            ])->save();

            return $dish;
        });
    }

    final public function delete(int $id): int
    {
        return $this->dish->destroy($id);
    }

    final public function updateImage(Dto $dto, Dish $dish): bool
    {
        $image = $this->dishImage->getByDishId(dishId: $dto->getDishId())->getByTypeId(typeId: $dto->getTypeId())->first();

        if (!is_null($image)) {
            if (Storage::exists($image['path'])) {
                Storage::delete($image['path']);
            }

            return $image->update(['path' => $dto->getPath()]);
        } else {
            return $this->createImages(dto: $dto, dish: $dish);
        }
    }

    final public function createImages(Dto $dto, Dish $dish): bool
    {
        $dishImage = $this->dishImage->create([
            'dish_id' => $dto->getDishId(),
            'type_id' => $dto->getTypeId(),
            'path' => $dto->getPath(),
        ]);
        $dish->dishImages()->save($dishImage);

        return $dish->dishImages()->exists();
    }
}
