<?php

namespace App\Http\Resources\Career\Candidate;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AllCandidateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'candidateId' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'cv' => $this->cv?Storage::disk('public')->url($this->cv):"",
            // 'cv' => $this->cv ? asset('storage/' . $this->cv) : "",
            'coverLetter' => $this->cover_letter??"",
            'careerName' => $this->career->title,
        ];
    }
}
