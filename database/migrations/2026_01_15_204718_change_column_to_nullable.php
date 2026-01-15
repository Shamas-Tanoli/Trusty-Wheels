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
        Schema::table('service_job_passenger_track', function (Blueprint $table) {
             $table->string('pickup_trip_one',50)->nullable()->change();
             $table->string('dropoff_trip_one',50)->nullable()->change();


             $table->string('pickup_trip_two',50)->nullable()->change();
                $table->string('dropoff_trip_two',50)->nullable()->change();
        });

        Schema::table('service_job_trip_track', function (Blueprint $table) {
           
            $table->string('trip_one_status',50)->nullable()->change(); 
            $table->string('trip_two_status',50)->nullable()->change(); 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('service_job_passenger_track', function (Blueprint $table) {
            $table->string('pickup_trip_one',50)->nullable(false)->change();
            $table->string('dropoff_trip_one',50)->nullable(false)->change();
            $table->string('pickup_trip_two',50)->nullable(false)->change();
            $table->string('dropoff_trip_two',50)->nullable(false)->change();
        });
        Schema::table('service_job_trip_track', function (Blueprint $table) {
            $table->string('trip_one_status',50)->nullable(false)->change(); 
            $table->string('trip_two_status',50)->nullable(false)->change(); 
        });
        
    }
};
