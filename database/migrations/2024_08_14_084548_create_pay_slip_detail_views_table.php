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
        Schema::create('pay_slip_detail_views', function (Blueprint $table) {
            $table->id();
            $table->date('for_month');
            $table->decimal('overtime_hours', 5, 2)->nullable();
            $table->foreignId('mas_employee_id')->index()->constrained();
            $table->integer('basic_pay', false, true);
            $table->string('Group_Savings_Linked_Insurance', 100)->nullable();
            $table->string('Health_Tax', 100)->nullable();
            $table->string('Medical_Allowance', 100)->nullable();
            $table->string('Project_Allowance', 100)->nullable();
            $table->string('Provident_Fund', 100)->nullable();
            $table->string('Staff_Initiative_for_Financial_Assistance', 100)->nullable();
            $table->string('Tax_Deducted_at_Source', 100)->nullable();
            $table->decimal('net_pay', 20, 2)->nullable();
            $table->decimal('gross_pay', 20, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_slip_detail_views');
    }
};
