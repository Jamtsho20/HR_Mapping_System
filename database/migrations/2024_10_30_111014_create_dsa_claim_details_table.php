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
        Schema::create('dsa_claim_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dsa_claim_id')->constrained('dsa_claim_applications')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->date('from_date');
            $table->date('to_date');
            $table->string('from_location');
            $table->string('to_location');
            $table->decimal('total_days', 10, 2);
            $table->decimal('daily_allowance', 10, 2)->comment('DA');
            $table->decimal('travel_allowance', 10, 2)->comment('TA');
            $table->decimal('total_amount', 10, 2);
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dsa_claim_details');
    }
};
