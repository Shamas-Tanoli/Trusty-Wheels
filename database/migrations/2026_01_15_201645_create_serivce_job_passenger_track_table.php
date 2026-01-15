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
        Schema::create('service_job_passenger_track', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_job_track_id')->constrained('service_job_track')->onDelete('cascade');
            $table->foreignId('passenger_id')->constrained('passengers')->onDelete('cascade');
            $table->string('status',50); 
            $table->string('pickup_trip_one',50);
            $table->string('dropoff_trip_one',50);
            $table->string('pickup_trip_two',50);
            $table->string('dropoff_trip_two',50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serivce_job_passenger_track');
    }
};
