<?php

namespace App\Contracts;

interface StatusMailNotifiable
{
    /**
     * 寄送預約狀態更新通知（美容師或管理員操作後觸發）
     */
    public function sendStatusUpdate(int $appointmentId, string $status): void;
}
