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
        Schema::create('ads_image', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ads_id');
            $table->foreign('ads_id')->references('id')->on('ads')->onDelete('cascade');
            $table->string('images')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads_image');
    }
};
