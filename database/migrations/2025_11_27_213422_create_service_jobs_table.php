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

            $table->string('status');     // pending, assigned, completed etc.

           
// passsenger id ka table alag 

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
