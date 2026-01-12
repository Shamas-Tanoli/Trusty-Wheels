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
         Schema::create('booking_types', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->timestamps();
        });

        Schema::create('booking', function (Blueprint $table) {
            $table->id();
           
            $table->foreignId('booking_type_id')->constrained('booking_types')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_time_id')->constrained('service_times')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('town_id')->constrained('towns')->cascadeOnDelete();
            $table->string('status');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
         Schema::dropIfExists('booking_types');
    }
};
