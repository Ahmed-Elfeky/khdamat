<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            // المستخدم اللي أضاف التقييم
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // مزود الخدمة اللي تم تقييمه
            $table->foreignId('service_provider_id')->constrained('users')->onDelete('cascade');
            // التقييم من 1 إلى 5
            $table->unsignedTinyInteger('rating');
            // تعليق المستخدم
            $table->text('comment')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
