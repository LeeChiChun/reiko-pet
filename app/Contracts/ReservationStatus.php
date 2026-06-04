<?php

namespace App\Contracts;

interface ReservationStatus
{
    public function label(): string;
    public function color(): string;
    public function canCancel(): bool;
}
