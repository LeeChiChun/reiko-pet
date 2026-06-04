<?php

namespace App\Contracts;

interface RefundInterface
{
    /**
     * 依交易 ID 發起退款，回傳是否成功
     */
    public function processRefund(string $transactionId): bool;
}
