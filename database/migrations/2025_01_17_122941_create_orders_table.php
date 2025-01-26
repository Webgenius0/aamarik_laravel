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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('treatment_id')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->boolean('tracked')->default(false);
            $table->decimal('royal_mail_tracked_price',8,2)->default(0);
            $table->decimal('sub_total', 8, 2)->default(0);
            $table->decimal('discount', 8, 2)->default(0);
            $table->decimal('total_price', 8, 2)->default(0);
            $table->decimal('pay_amount', 8, 2)->default(0);
            $table->decimal('due_amount', 8, 2)->default(0);
            $table->string('stripe_payment_id')->unique()->nullable();
            $table->boolean('subscription')->default(false);
            $table->string('prescription')->nullable();
            $table->text('note')->nullable();
            $table->enum('status', [
                'pending',    // Order has been created but not processed yet
                'paid',       // Payment has been received, but the order is not yet shipped
                'processing', // Order is being prepared, packed, or queued for shipment
                'shipped',    // Order has been shipped
                'delivered',  // Order has been delivered to the customer
                'cancelled',  // Order was cancelled before it was shipped
                'failed'      // Order failed, payment issues, or other problems during the process
            ])->default('pending');
            $table->timestamps();

            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('treatment_id')->references('id')->on('treatments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
