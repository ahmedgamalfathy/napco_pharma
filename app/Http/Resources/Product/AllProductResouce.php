<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class AllProductResouce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'productId' => $this->id,
            'name' => $this->name,
            'isActive' => $this->is_active,
            'image' => $this->images->first() != null? Storage::disk('public')->url($this->images->first()->path):"",
        ];
    }
}
