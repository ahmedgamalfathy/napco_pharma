<?php

namespace App\Http\Resources\user;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\User\UserResouce;
class AllUserCollection extends ResourceCollection
{
    private $pagination;
    public function __construct($resource)
    {
       $this->pagination =[
        'total' => $resource->total(),
        'count' => $resource->count(),
        'per_page' => $resource->perPage(),
        'current_page' => $resource->currentPage(),
        'total_pages' => $resource->lastPage()
       ];
       $resource = $resource->getCollection();
       parent::__construct($resource);
    }
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "result" => [
                'users' => UserResouce::collection(($this->collection)->values()->all()),
            ],
            'pagination' => $this->pagination
        ];
    }
}
