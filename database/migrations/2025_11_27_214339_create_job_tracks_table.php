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
        Schema::create('serice_job_tracks', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('driver_id');    // Driver reference
            $table->unsignedBigInteger('service_job_id');       // Service job reference
            $table->unsignedBigInteger('vehicle_id');   // Vehicle reference

            $table->string('status')
                  ->default('pending');

            // --- Passenger Going Trip ---
            $table->string('going_pickup_location')->nullable();
            $table->string('going_drop_location')->nullable();

            // --- Passenger Return Trip ---
            $table->string('return_pickup_location')->nullable();
            $table->string('return_drop_location')->nullable();

            // Time of tracking / log
            $table->timestamp('tracking_time')->nullable();

           

            
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
            $table->foreign('service_job_id')->references('id')->on('service_jobs')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('service_vehicles')->onDelete('cascade');
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serice_job_tracks');
    }
};
