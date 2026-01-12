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
        Schema::create('service_job_passengers', function (Blueprint $table) {
             $table->id();

    $table->unsignedBigInteger('service_job_id');
    $table->unsignedBigInteger('passenger_id');

    // passenger status (present, absent, leave, etc.)
    $table->string('status');

    $table->timestamps();

    // foreign keys
    $table->foreign('service_job_id')->references('id')->on('service_jobs')->onDelete('cascade');
    $table->foreign('passenger_id')->references('id')->on('booking_passengers')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_job_passengers');
    }
};
