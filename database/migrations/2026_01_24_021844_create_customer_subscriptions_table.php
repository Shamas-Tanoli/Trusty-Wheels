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
        Schema::create('customer_subscriptions', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('booking_id')->constrained('booking'); 
            $table->foreignId('passenger_id')->constrained('booking_passengers'); 
            $table->foreignId('plan_id')->constrained('plans');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('price', 10, 2); 
            $table->enum('payment_type', ['one_time','weekly','monthly']);
            $table->enum('status', ['active','paused','cancelled'])->default('active');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_subscriptions');
    }
};
