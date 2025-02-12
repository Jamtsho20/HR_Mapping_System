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
        Schema::create('dsa_claim_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_authorization_id')->nullable()->constrained('travel_authorization_applications')->restrictOnDelete()->restrictOnUpdate();
            $table->foreignId('advance_application_id')->nullable()->constrained()->restrictOnDelete()->restrictOnUpdate();
            $table->foreignId('dsa_claim_id')->constrained('dsa_claim_applications')->restrictOnDelete()->cascadeOnUpdate();
            $table->decimal('advance_amount', 12, 2)->nullable();
            $table->decimal('ta_amount', 12, 2)->nullable();
            $table->json('attachment')->nullable()->comment('Relevant attachment path, stored as JSON');
            $table->integer('number_of_days')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dsa_claim_mappings');
    }
};
