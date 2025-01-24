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
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('treatment_id')->nullable();
            $table->boolean('tracked')->default(false);
            $table->decimal('royal_mail_tracked_price',8,2)->default(0);
            $table->decimal('total_price', 8, 2)->default(0);
            $table->boolean('subscription')->default(false);
            $table->string('prescription')->nullable();
            $table->text('note')->nullable();
            $table->enum('status', ['pending','accepted','failed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->timestamps();
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
