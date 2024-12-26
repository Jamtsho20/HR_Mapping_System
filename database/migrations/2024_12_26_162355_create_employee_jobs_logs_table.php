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
        Schema::create('employee_jobs_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_employee_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignId('mas_department_id')->index()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('mas_section_id')->index()->nullable()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('mas_designation_id')->index()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->boolean('suffix')->default(0)->comment('eg: senior(Sr.)');
            $table->foreignId('mas_grade_id')->index()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('mas_grade_step_id')->index()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->boolean('has_probation')->default(1);
            $table->foreignId('mas_employment_type_id')->index()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('immediate_supervisor')->index()->nullable()->constrained('mas_employees')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('mas_office_id')->index()->constrained()->cascadeOnUpdate()->restrictOnDelete()->comment('job location');
            $table->integer('basic_pay');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_jobs_logs');
    }
};
