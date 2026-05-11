<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customer_invoices', function (Blueprint $table) {
            DB::statement("
            ALTER TABLE customer_invoices 
            MODIFY status ENUM('pending','paid','partial','cancelled') 
            DEFAULT 'pending'
        ");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_invoices', function (Blueprint $table) {
            //
        });
    }
};
