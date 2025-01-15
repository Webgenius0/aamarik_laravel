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
        Schema::create('medicine_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medicine_id');
            $table->foreign('medicine_id')->references('id')->on('medicines')->onDelete('cascade');
            $table->string('avatar');
            $table->enum('form', ['tablet', 'liquid', 'capsule', 'inhaler', 'syrup', 'ointment']);
            $table->string('dosage')->nullable()->comment('Dosage per unit (e.g., 500mg for a tablet or 10ml for syrup)');
            $table->string('unit')->nullable()->comment('Unit of measurement (e.g., mg, ml, etc.)');
            $table->decimal('price', 8, 2)->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_details');
    }
};
