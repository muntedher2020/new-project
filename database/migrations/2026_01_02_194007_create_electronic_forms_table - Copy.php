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
            $table->string('title')->comment('العنوان');
            $table->string('description')->comment('الوصف');
            $table->string('name_1')->comment('الاسم الاول');
            $table->string('name_2')->comment('الاسم الثاني');
            $table->string('name_3')->comment('الاسم الثالث');
            $table->string('name_4')->comment('الاسم الرابع');
            $table->string('name_5')->comment('اللقب');
            $table->string('place_of_birth')->comment('مكان الولادة');
            $table->date('date_of_birth')->comment('تاريخ الولادة');
            $table->string('mother_name_1')->comment('اسم الام');
            $table->string('mother_name_2')->comment('اسم والد الام');
            $table->string('mother_name_3')->comment('اسم جد الام');
            $table->string('district')->comment('القضاء');
            $table->string('region')->comment('المنطقة');
            $table->string('academic_achievement')->comment('التحصيل الدراسي');
            $table->string('specialization')->comment('التخصص الدقيق');
            $table->string('graduation_year')->comment('سنة التخرج');
            $table->string('national_ID_number')->comment('رقم البطاقة الوطنية');
            $table->string('marital_status')->comment('الحالة الزوجية');
            $table->string('partners_name')->comment('اسم الشريك');
            $table->string('phone_number')->comment('رقم الهاتف');
            $table->timestamps();
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
