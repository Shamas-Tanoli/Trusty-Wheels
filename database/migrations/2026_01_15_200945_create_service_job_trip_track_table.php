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
        Schema::create('service_job_trip_track', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_job_track_id')->constrained('service_job_track')->onDelete('cascade');
            $table->foreignId('driver_id')->constrained('drivers')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained('service_vehicles')->onDelete('cascade');
            $table->string('trip_one_status',50); 
            $table->string('trip_two_status',50); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_job_trip_track');
    }
};
