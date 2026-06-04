<?php

namespace App\Contracts;

interface BookingMailNotifiable
{
    /**
     * 寄送預約成功確認信給顧客
     */
    public function sendBookingConfirmation(int $appointmentId): void;

    /**
     * 寄送預約取消通知給顧客
     */
    public function sendCancellation(int $appointmentId): void;
}
