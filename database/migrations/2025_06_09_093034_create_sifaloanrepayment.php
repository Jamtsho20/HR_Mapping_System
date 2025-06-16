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
        Schema::create('sifaloanrepayment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advance_application_id')->constrained()->onDelete('cascade');
            $table->integer('repayment_number');
            $table->date('month');
            $table->decimal('opening_balance', 12, 2);
            $table->decimal('monthly_emi_amount', 12, 2);
            $table->decimal('interest_charged', 12, 2);
            $table->decimal('principal_repaid', 12, 2);
            $table->decimal('closing_balance', 12, 2);
            $table->decimal('percentage_outstanding', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sifaloanrepayment');
    }
};
