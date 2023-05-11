<?php

declare(strict_types=1);

namespace App\Http\Controllers\Like;

use App\Http\Controllers\Controller;
use App\Http\Requests\Likes\LikeRequest;
use App\Http\Requests\Likes\UnlikeRequest;
use App\Http\Resources\ResponseResource;
use App\Http\Resources\UserResource;
use App\Service\LikeService;

class LikeController extends Controller
{
    public function __construct(protected readonly LikeService $service)
    {
    }

    final public function store(LikeRequest $request): ResponseResource
    {
        $user = $this->service->store(likeable: $request->likeable());

        return new ResponseResource(
            resource: new UserResource(resource: $user)
        );
    }

    final public function delete(int $userId, UnlikeRequest $request): ResponseResource
    {
        $user = $this->service->delete(id: $userId, likeable: $request->likeable());

        return new ResponseResource(
            resource: new UserResource(resource: $user)
        );
    }
}
