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
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('invoice_id')->constrained('customer_invoices')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
                $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
        $table->enum('payment_method', ['cash','bank','jazzcash','easypaisa','card']);
            $table->string('transaction_ref')->nullable();
            $table->enum('status', ['success','failed','pending'])->default('success');
            $table->timestamp('paid_at');
            $table->timestamps(0); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
