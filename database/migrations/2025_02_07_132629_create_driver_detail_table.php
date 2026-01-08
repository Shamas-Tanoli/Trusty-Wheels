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

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('towns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });
        
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            
            $table->string('father_name');
            $table->string('cnic')->unique();
            $table->string('contact');
            $table->string('emergency_contact');
            $table->string('blood_group'); 
            $table->string('address');
            
            $table->enum('verification_status', ['active', 'in-active'])->default('active');
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
         
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
          Schema::dropIfExists('cities');
        Schema::dropIfExists('driver_detail');
    }
};
