<?php

namespace App\Contracts;

interface MailNotificationInterface
{
    public function sendBookingConfirmation(int $appointmentId): void;

    public function sendCancellation(int $appointmentId): void;

    public function sendStatusUpdate(int $appointmentId, string $status): void;
}
