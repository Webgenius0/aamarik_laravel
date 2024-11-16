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
        Schema::create('dynamic_pages', function (Blueprint $table) {
            $table->id();
            $table->string( 'page_title', 200 );
            $table->string( 'page_slug', 200 );
            $table->text( 'page_content' );
            $table->tinyInteger( 'status' )->default( 1 )->comment( '1 = Active / 0 = Deactivate' );
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dynamic_pages');
    }
};
