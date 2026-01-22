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
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type',['percentage','fixed']);
            $table->decimal('value',8,2);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('usage_limit')->nullable()->comment('Number of times the promo code can be used');
            $table->integer('used_count')->default(0)->comment('Number of times the promo code has been used');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
    }
};
