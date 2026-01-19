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
        Schema::create('form_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id');
            $table->unsignedBigInteger('user_id')->nullable()->comment('المستخدم إذا كان مسجل دخول');
            $table->json('response_data')->comment('بيانات الإجابة');
            $table->enum('status', ['pending', 'approved', 'rejected', 'under_review'])->default('pending');
            $table->text('notes')->nullable()->comment('ملاحظات إدارية');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('browser_fingerprint', 32)->nullable()->index();
            $table->string('submission_hash', 32)->nullable()->unique();
            $table->timestamps();

            $table->foreign('electronic_forms_id')->references('id')->on('electronic_forms')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['electronic_forms_id', 'status']);
            $table->index(['ip_address', 'electronic_forms_id']);
            $table->index(['browser_fingerprint', 'electronic_forms_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_responses');
    }
};
