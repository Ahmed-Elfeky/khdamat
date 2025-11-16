<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   public function up()
{
    Schema::create('service_ads', function (Blueprint $table) {
        $table->id();

        // بيانات الإعلان الأساسية
        $table->string('title');
        $table->text('description')->nullable();
        $table->decimal('price', 10, 2)->nullable();

        // في حالة الخدمات (مقابل مادي)
        $table->decimal('reward', 10, 2)->default(0)->nullable();

        // في حالة التبادل
        $table->string('exchange')->nullable();

        // نوع الإعلان (عرض - خدمة - تبادل - طلب)
        $table->enum('type', ['ads', 'service', 'exchange', 'request'])->default('ads');

        // التصنيف - المدينة - المنطقة
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->foreignId('city_id')->nullable()->constrained()->onDelete('set null');
        $table->foreignId('region_id')->nullable()->constrained()->onDelete('set null');

        // المستخدم صاحب الإعلان
        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        // حالة الإعلان
        $table->boolean('is_active')->default(true);

        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('service_ads');
}
};
