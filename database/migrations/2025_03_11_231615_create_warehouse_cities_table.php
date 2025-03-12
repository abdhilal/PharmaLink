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
        Schema::create('warehouse_cities', function (Blueprint $table) {
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade'); // معرف المستودع
            $table->foreignId('city_id')->constrained('cities')->onDelete('cascade'); // معرف المدينة
            $table->primary(['warehouse_id', 'city_id']); // المفتاح الأساسي المركب
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_cities');
    }
};
