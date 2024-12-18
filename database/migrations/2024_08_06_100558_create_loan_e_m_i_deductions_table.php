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
        Schema::create('loan_e_m_i_deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId("mas_pay_head_id")->index()->constrained();
            $table->foreignId("mas_employee_id")->index()->constrained();
            $table->date("start_date");
            $table->date("end_date")->nullable();
            $table->decimal("amount", 20,2);
            $table->decimal("loan_number", 20,2)->comment("accpunt nunber for loan");
            $table->foreignId('loan_type_id')->index()->constrained('mas_loan_types')->cascadeOnUpdate()->restrictOnDelete();
            $table->boolean("recurring")->default(0)->comment("1 for Yes, 0 for No");
            $table->integer("recurring_months")->nullable();
            $table->text("remarks")->nullable();
            $table->boolean("is_paid_off")->default(0)->comment("1 for Yes, 0 for No");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_e_m_i_deductions');
    }
};
