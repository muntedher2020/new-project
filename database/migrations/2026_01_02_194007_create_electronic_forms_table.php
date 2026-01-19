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
        Schema::create('electronic_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('معرف المستخدم');
            $table->string('title', 255)->comment('عنوان الاستمارة');
            $table->string('slug', 255)->unique()->comment('رابط فريد للاستمارة');
            $table->text('description')->nullable()->comment('وصف مفصل');
            $table->json('form_fields')->nullable()->comment('الحقول المخصصة للاستمارة');
            $table->string('form_type')->nullable()->comment('نوع للاستمارة');
            $table->boolean('active')->default(false)->comment('مفعل');
            $table->integer('max_responses')->nullable()->comment('الحد الأقصى للإجابات');
            $table->dateTime('start_date')->nullable()->comment('تاريخ بدء التعبئة');
            $table->dateTime('end_date')->nullable()->comment('تاريخ انتهاء التعبئة');
            $table->boolean('require_login')->default(false)->comment('يتطلب تسجيل دخول');
            $table->boolean('allow_multiple')->default(false)->comment('السماح بتقديمات متعددة');
            $table->json('settings')->nullable()->comment('إعدادات إضافية');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['active', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('electronic_forms');
    }
};
