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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('make_id')->constrained()->cascadeOnUpdate()->noActionOnDelete();
            $table->foreignId('vehicle_model_id')->constrained('vehicle_models')->cascadeOnUpdate()->noActionOnDelete();
            $table->foreignId('vehicle_type_id')->constrained('vehicle_types')->cascadeOnUpdate()->noActionOnDelete();
            $table->foreignId('location_id')->constrained('locations')->cascadeOnUpdate()->noActionOnDelete();
            $table->string('title');
            $table->text('description'); 
            $table->string('vin_nbr');
            $table->string('lic_plate_nbr');
            $table->string('transmission');
            $table->string('fuel_type');
            $table->string('door');
            $table->string('seats');
            $table->year('year');
            $table->decimal('rent',8,2);
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listing_car');
    }
};
