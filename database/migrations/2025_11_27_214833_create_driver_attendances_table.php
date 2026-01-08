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
        Schema::create('driver_attendances', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('driver_id');

            $table->date('attendance_date');               // Jaise: 2025-11-27
            $table->time('check_in_time')->nullable();     // Attendance lagane ka time
            $table->time('check_out_time')->nullable();

            $table->enum('status', ['present', 'absent', 'leave'])
                  ->default('absent');

            


            $table->foreign('driver_id')
                  ->references('id')->on('drivers')
                  ->onDelete('cascade');
                  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_attendances');
    }
};
