<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('type', ['percentage', 'fixed', 'buy_one_get_one'])->default('percentage');
            $table->unsignedInteger('discount_value')->default(0); // % or NT$ amount
            $table->unsignedInteger('minimum_amount')->default(0);  // minimum order total
            $table->enum('scope', ['all', 'grooming', 'accommodation', 'shop'])->default('all');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // null = universal
            $table->unsignedInteger('max_uses')->nullable();   // null = unlimited
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_no')->nullable();
            $table->unsignedInteger('amount_saved')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('coupons');
    }
};
