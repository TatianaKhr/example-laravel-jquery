<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tag;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResponseResource;
use App\Http\Resources\TagResource;
use App\Models\Tag;

class TagController extends Controller
{
    public function __construct(protected readonly Tag $tag)
    {
    }

    final public function index(): ResponseResource
    {
        return new ResponseResource(
            resource: TagResource::collection(resource: $this->tag->all()),
        );
    }
}
