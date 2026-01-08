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
        Schema::table('plans', function (Blueprint $table) {

            // ❌ old columns remove
            $table->dropColumn(['area_from', 'area_to', 'trip_type']);

            // ✅ new foreign keys add
            $table->foreignId('area_from_id')
                  ->after('price')
                  ->constrained('towns')
                  ->cascadeOnDelete();

            $table->foreignId('area_to_id')
                  ->after('area_from_id')
                  ->constrained('towns')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {

           
            $table->dropForeign(['area_from_id']);
            $table->dropForeign(['area_to_id']);

            $table->dropColumn(['area_from_id', 'area_to_id']);

           
            $table->string('area_from');
            $table->string('area_to');
            $table->enum('trip_type', ['single', 'round']);
        });
    }
};
