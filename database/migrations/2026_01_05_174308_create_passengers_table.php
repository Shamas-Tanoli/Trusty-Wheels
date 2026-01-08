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
       Schema::create('booking_passengers', function (Blueprint $table) {
           
            $table->id();

            // Foreign Key
            $table->foreignId('customer_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('booking_id')
                  ->constrained('booking')
                  ->cascadeOnDelete();

            // Passenger Info
            $table->string('name');

            // Pickup & Dropoff Time
            $table->string('pickup_time',250);
            $table->string('dropoff_time',250);

            // Pickup Location (Latitude & Longitude)
            $table->decimal('pickup_latitude', 10, 7);
            $table->decimal('pickup_longitude', 10, 7);

            // Dropoff Location (Latitude & Longitude)
            $table->decimal('dropoff_latitude', 10, 7);
            $table->decimal('dropoff_longitude', 10, 7);

            // Human Readable Locations
            $table->string('pickup_location');
            $table->string('dropoff_location');

            $table->timestamps();
        });
      
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_passengers');
    }
};
