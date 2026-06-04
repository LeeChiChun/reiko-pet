<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AccommodationRoom extends Model
{
    protected $fillable = [
        'name', 'slug', 'price_per_night', 'max_weight',
        'description', 'features', 'image_path', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'features'  => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function imageUrl(): ?string
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }

    public function featuresArray(): array
    {
        return $this->features ?? [];
    }
}
