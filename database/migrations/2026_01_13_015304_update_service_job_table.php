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
        Schema::table('service_jobs', function (Blueprint $table) {
            // drop unwanted columns
            $table->dropForeign(['service_timing_id']);
            $table->dropColumn(['service_timing_id']);

            // add new columns
          
            $table->date('job_date');
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
