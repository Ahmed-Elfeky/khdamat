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
        Schema::create('service_ad_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_ad_id')->constrained('service_ads')->onDelete('cascade');
            $table->string('file_path'); 
            $table->enum('type', ['image', 'video'])->default('image'); // نوع الملف
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_ad_media');
    }
};
