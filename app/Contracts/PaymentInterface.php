<?php

namespace App\Contracts;

interface PaymentInterface
{
    /**
     * 發起付款，回傳 ['success', 'transaction_id', 'amount']
     */
    public function processPayment(float $amount, array $metadata = []): array;
}
