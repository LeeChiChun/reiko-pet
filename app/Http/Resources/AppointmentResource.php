<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'status'         => $this->status->label(),
            'appointment_at' => $this->appointment_at->format('Y-m-d H:i'),
            'total_price'    => $this->total_price,
            'pet'            => [
                'name' => $this->whenLoaded('pet', fn() => $this->pet->name),
                'type' => $this->whenLoaded('pet', fn() => $this->pet->type),
            ],
            'service'        => [
                'name'  => $this->whenLoaded('service', fn() => $this->service->name),
                'price' => $this->whenLoaded('service', fn() => $this->service->price),
            ],
            'store'          => [
                'name'    => $this->whenLoaded('store', fn() => $this->store->name),
                'address' => $this->whenLoaded('store', fn() => $this->store->address),
            ],
        ];
    }
}
