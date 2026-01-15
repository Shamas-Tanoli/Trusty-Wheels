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
        Schema::create('service_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id');          // driver reference
            $table->unsignedBigInteger('vehicle_id');         // service_vehicle reference
            $table->unsignedBigInteger('service_timing_id');  // timing reference

            $table->string('status');      // pending, assigned, completed etc.

           
// passsenger id ka table alag 


// job 

// driver id
// vehicle id 
// job status {job is active or not }
// job start time
// job ke multiple passangers hungy 


// job pasagner table

// jobs id 
// passenger id 
// status (aj bacha pickup jye ga ya ni )

// job schedule 
//  job id 
// status (aj ke din  ki job complete hogai hai ya nahi)


// job schedule detail 
// job id
// vehicle id
// driver id
// trip 1 completed ya ni
// trip 2 completed ya ni


// job schedule detail 
// job schedule id
// passenger id
// status (bacha gye ya ni aj )
// pickup trip one
// dropoff trip one
// pickup trip two
// dropoff trip two







            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('service_vehicles')->onDelete('cascade');
            $table->foreign('service_timing_id')->references('id')->on('service_times')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_jobs');
    }
};
