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
        Schema::create('employee_salary_savings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->references('id')->constrained('mas_employees');
            $table->foreignId('pay_head_id')->references('id')->constrained('mas_pay_heads');
            $table->float('amount');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_salary_savings');
    }
};
