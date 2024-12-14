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
        Schema::create('location_group_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_group_id');
            $table->string('avatar');
            $table->timestamps();
            $table->softDeletes();

            //key
            $table->foreign('location_group_id')->references('id')->on('location_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_group_images');
    }
};
