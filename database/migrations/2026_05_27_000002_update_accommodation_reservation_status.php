<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 舊的 'paid' 對應新的 'confirmed'
        DB::table('accommodation_reservations')
            ->where('status', 'paid')
            ->update(['status' => 'confirmed']);
    }

    public function down(): void
    {
        DB::table('accommodation_reservations')
            ->where('status', 'confirmed')
            ->update(['status' => 'paid']);
    }
};
