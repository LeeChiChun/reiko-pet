<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'type'        => $this->type,
            'breed'       => $this->breed,
            'gender'      => $this->gender,
            'age'         => $this->age,
            'weight'      => $this->weight,
            'health_note' => $this->health_note,
        ];
    }
}
