<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Unique coupon code
            $table->enum('discount_type', ['fixed', 'percentage']); // Fixed or percentage discount
            $table->decimal('discount_amount', 8, 2); // Discount value
            $table->unsignedInteger('usage_limit')->default(1); // Total number of times the coupon can be used
            $table->unsignedInteger('used_count')->default(0); // Track how many times the coupon is used
            $table->timestamp('start_date')->nullable(); // Coupon start date
            $table->timestamp('end_date')->nullable(); // Coupon end date
            $table->boolean('status')->default(true); // Coupon active status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
