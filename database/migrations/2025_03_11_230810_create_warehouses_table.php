<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id(); // معرف المستودع
            $table->foreignId('user_id')->constrained('users'); // معرف المستخدم المرتبط
            $table->string('phone')->nullable(); // رقم الهاتف (اختياري)
            $table->string('address')->nullable(); // العنوان (اختياري)
            $table->timestamps(); // تواريخ الإنشاء والتحديث
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
