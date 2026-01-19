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
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id');
            $table->string('label', 255)->comment('اسم الحقل المعروض');
            $table->string('name', 255)->comment('اسم الحقل الفني');
            $table->string('type', 50)->comment('نوع الحقل: text, email, number, select, etc.');
            $table->boolean('required')->default(false)->comment('حقل مطلوب');
            $table->string('placeholder')->nullable()->comment('نص توضيحي');
            $table->string('description')->nullable()->comment('شرح توضيحي');
            $table->json('options')->nullable()->comment('خيارات للحقول من نوع select, radio, checkbox');
            $table->integer('sort_order')->default(0)->comment('ترتيب الحقل');
            $table->json('validation_rules')->nullable()->comment('قواعد التحقق');
            $table->json('settings')->nullable()->comment('إعدادات إضافية للحقل');
            $table->timestamps();

            $table->foreign('form_id')->references('id')->on('electronic_forms')->onDelete('cascade');
            $table->index(['form_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};
