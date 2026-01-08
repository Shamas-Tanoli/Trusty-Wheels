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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
             $table->string('name');                        // Plan Name
            $table->string('area_from');                   // Pickup Area
            $table->string('area_to');                     // Drop Area
            $table->decimal('price', 10, 2);               // Plan Price

            $table->enum('trip_type', ['single', 'round']);  // single trip OR round trip
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
