<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
    public function up(): void
    {
        Schema::create('service_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id');         
            $table->unsignedBigInteger('vehicle_id');         
            $table->unsignedBigInteger('service_timing_id');  
            $table->string('status');   
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('service_vehicles')->onDelete('cascade');
            $table->foreign('service_timing_id')->references('id')->on('service_times')->onDelete('cascade');
            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('service_jobs');
    }
};
