<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentAddon extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id', 'addon_service_id', 'price',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function addonService()
    {
        return $this->belongsTo(AddonService::class);
    }
}
