<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            // نوع المستخدم
            $table->enum('role', ['customer', 'provider', 'admin'])->default('customer');

            // بيانات أساسية
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // نظام OTP للتحقق
            $table->string('otp_code')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->boolean('is_verified')->default(false);

            // بيانات إضافية اختيارية
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable();

            // بيانات مخصصة لمزود الخدمة (اختياري)
            $table->string('specialization')->nullable(); // نوع الخدمة مثلاً: كهرباء، سباكة...
            $table->string('whatsapp')->nullable(); // رقم واتساب للتواصل

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
