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
         Schema::create('visitors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sensor_id');
            $table->date('date');
            $table->unsignedInteger('count');
            $table->timestamps();
            
            $table->foreign('sensor_id')->references('id')->on('sensors')->onDelete('cascade');
            $table->unique(['sensor_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
