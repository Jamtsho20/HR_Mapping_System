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
        Schema::create('bank_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_employee_id')->index()->constrained();
            $table->string('bank', 50);
            $table->string('loan_type');
            $table->string('account_number');
            $table->string('principal_amount');
            $table->string('start_month')->comment('starting month for deduction');
            $table->foreignId('created_by')->index()->constrained('mas_employees');
            $table->foreignId('updated_by')->index()->nullable()->constrained('mas_employees');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_loans');
    }
};
