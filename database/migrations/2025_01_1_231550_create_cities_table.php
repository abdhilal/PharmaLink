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
        Schema::create('cities', function (Blueprint $table) {
            $table->id(); // معرف المدينة
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ربط المستخدم (صيدلي أو مستودع)
            $table->string('name'); // اسم المدينة (شكلي)
            $table->decimal('latitude', 10, 8)->nullable(); // خط العرض للصيدلية أو المستودع
            $table->decimal('longitude', 11, 8)->nullable(); // خط الطول للصيدلية أو المستودع
            $table->decimal('range_east', 10, 2)->nullable(); // النطاق شرقًا (للمستودعات فقط)
            $table->decimal('range_west', 10, 2)->nullable(); // النطاق غربًا (للمستودعات فقط)
            $table->decimal('range_north', 10, 2)->nullable(); // النطاق شمالًا (للمستودعات فقط)
            $table->decimal('range_south', 10, 2)->nullable(); // النطاق جنوبًا (للمستودعات فقط)
            $table->timestamps(); // تاريخ الإنشاء والتحديث
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
