<?php

namespace App\Contracts;

interface Reservable
{
    /** 確認這筆預約是否屬於指定使用者 */
    public function ownedBy(int $userId): bool;

    /** 目前狀態是否允許取消 */
    public function canBeCancelled(): bool;

    /** 執行取消，更新狀態至已取消 */
    public function cancel(): void;
}
