<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->integer('predicted_demand');
            $table->integer('recommended_stock');
            $table->date('prediction_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_predictions');
    }
};
