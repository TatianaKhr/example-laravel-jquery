<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DishCollection extends ResourceCollection
{
    public $collects = DishResource::class;

    final public function toArray($request): array
    {
        $paginationLinks = $this->getPaginationLinks($request);

        return [
            'data' => $this->collection,
            'links' => $paginationLinks,
        ];
    }

    final public function getPaginationLinks(Request $request): array
    {
        $paginationLinks = [];
        $currentPage = $this->currentPage();
        $lastPage = $this->lastPage();

        $paginationLinks[] = [
            'url' => $this->url($currentPage - 1),
            'active' => $currentPage > 1,
        ];

        for ($page = 1; $page <= $lastPage; $page++) {
            $paginationLinks[] = [
                'url' => $this->url($page),
                'active' => $page == $currentPage,
            ];
        }

        $paginationLinks[] = [
            'url' => $this->url($currentPage + 1),
            'active' => $currentPage < $lastPage,
        ];

        return $paginationLinks;
    }
}
