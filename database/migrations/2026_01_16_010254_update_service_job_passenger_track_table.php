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
         Schema::create('service_job_passenger_track', function (Blueprint $table) {
            $table->foreignId('service_job_passengers_id')->constrained('service_job_passengers')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};



// subscriptions customer table 
// customer id 
// amount = total of all passengers
// discount = total discount applied
// paid_amount = amount - discount
// /booking  id
// start _date
// end_date
// payment_method = cash/card/wallet
// status = pending/paid/partial
// payment_for_month = YYYY-MM-01

// har month ki phli date ko -


