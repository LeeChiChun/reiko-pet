<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accommodation_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('guest_name');
            $table->string('guest_phone');
            $table->string('guest_email');
            $table->string('pet_name');
            $table->string('room_type');   // basic | standard | comfort | penthouse
            $table->integer('price_per_night');
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('nights');
            $table->integer('total_price');
            $table->string('status')->default('pending'); // pending | paid | cancelled
            $table->string('order_no')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accommodation_reservations');
    }
};
