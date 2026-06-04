<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddonService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price', 'description', 'applicable_to', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function appointmentAddons()
    {
        return $this->hasMany(AppointmentAddon::class);
    }
}
