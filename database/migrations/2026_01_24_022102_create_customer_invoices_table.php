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
        Schema::create('customer_invoices', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('customer_id')->constrained('users'); 
            $table->date('invoice_for_date');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('discounted_total', 10, 2);
            $table->decimal('after_discount', 10, 2);

            $table->enum('discount_type',['promocode','offer']);
            $table->enum('status', ['pending','paid','partial','overdue'])->default('pending');
            $table->date('due_date');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps(); 
            $table->unique(['customer_id', 'invoice_for_date'], 'unique_invoice_per_customer_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_invoices');
    }
};
