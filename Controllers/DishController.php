<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dish;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dish\FilterRequest;
use App\Http\Requests\Dish\StoreRequest;
use App\Http\Requests\Dish\UpdateRequest;
use App\Http\Resources\DishCollection;
use App\Http\Resources\DishResource;
use App\Http\Resources\ResponseResource;
use App\Service\DishService;

class DishController extends Controller
{
    public function __construct(protected readonly DishService $service)
    {
    }

    final public function index(FilterRequest $request): ResponseResource
    {
        return new ResponseResource(
            resource: new DishCollection(resource: $this->service->list(dto: $request->dto())),
        );
    }

    final public function show(int $id): ResponseResource
    {
        return new ResponseResource(
            resource: new DishResource(resource: $this->service->show(id: $id))
        );
    }

    final public function store(StoreRequest $request): ResponseResource
    {
        return new ResponseResource(
            resource: new DishResource(resource: $this->service->store(dto: $request->dto())),
        );
    }

    final public function edit(int $id): ResponseResource
    {
        return new ResponseResource(
            resource: new DishResource(resource: $this->service->show(id: $id))
        );
    }

    final public function update(int $id, UpdateRequest $request): ResponseResource
    {
        return new ResponseResource(
            resource: new DishResource(resource: $this->service->update(dto: $request->dto(), id: $id))
        );
    }

    final public function delete(int $id): ResponseResource
    {
        $this->service->delete(id: $id);

        return new ResponseResource(
            message: 'success'
        );
    }
}
