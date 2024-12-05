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
        Schema::create('advance_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advance_application_id')->constrained('advance_applications')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('budget_code_id')->nullable()->constrained('budget_codes')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->foreignId('dzongkhag_id')->nullable()->constrained('mas_dzongkhags')->cascadeOnUpdate()->restrictOnDelete()->comment('required only if advance_type is advance to staff');
            $table->string('site_location');
            $table->decimal('amount_required', 12, 2);
            $table->text('purpose')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advance_details');
    }
};
