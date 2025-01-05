<?php

namespace App\Http\Resources\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserallResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "UserName"=>$this->name,
            "Email"=>$this->email,
            "IsActive"=>$this->status,
            "Created"=>Carbon::parse($this->creaetd_at)->diffForHumans()
        ];
    }
}
