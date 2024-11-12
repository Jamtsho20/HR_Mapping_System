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
        Schema::create('expense_fuel_claim_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exp_fuel_claim_id')->index()->constrained('expense_fuel_claim_applications')->cascadeOnDelete()->cascadeOnUpdate();
            $table->date('date')->comment('travelled date');
            $table->decimal('initial_reading', 10, 2)->comment('km reading of vehicle before starting journey');
            $table->decimal('final_reading', 10, 2)->comment('km reading of vehicle after end of journey');
            $table->decimal('fuel_qty', 10, 2)->comment('fuel consumed');
            // $table->decimal('mileage', 10, 2)->comment('mileage covered on that particular date as Km.');
            $table->decimal('fuel_rate', 10, 2)->comment('price of fuel.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_fuel_claim_details');
    }
};
