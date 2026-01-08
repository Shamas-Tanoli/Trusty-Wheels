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
        Schema::create('service_vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name');                         // vehicle name / owner name
            $table->string('number_plate')->nullable();     // nbr plate
            $table->string('make')->nullable();             // manufacturer (e.g., Toyota)
            $table->string('model')->nullable();            // model (e.g., Corolla)
            $table->string('color')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_vehicles');
    }
};
