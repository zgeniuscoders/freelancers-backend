<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "budget" =>$this->budget,
            "durationDays"=>$this->duration_days,
            "user"=>new UserResource($this->whenLoaded("owner")),
            "category"=>new CategoryResource($this->whenLoaded("category")),
            "createdAt"=>$this->created_at
        ];
    }
}
