<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medicine_expiry', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->date('expiry_date');
            $table->integer('quantity');
            $table->string('batch_number');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medicine_expiry');
    }
};
