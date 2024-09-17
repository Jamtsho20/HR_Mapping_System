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
        Schema::create('mas_employee_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_employee_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignId('mas_department_id')->index()->constrained();
            $table->foreignId('mas_section_id')->index()->constrained();
            $table->foreignId('mas_designation_id')->index()->constrained();
            $table->foreignId('mas_grade_id')->index()->constrained();
            $table->foreignId('mas_grade_step_id')->index()->constrained();
            $table->foreignId('mas_employment_type_id')->index()->constrained();
            $table->foreignId('immediate_supervisor')->index()->nullable()->constrained('mas_employees')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('job_location', 100)->nullable();
            $table->integer('basic_pay');
            $table->string('bank', 50);
            $table->string('account_number');
            $table->string('pf_number', 100);
            $table->string('tpn_number', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mas_employee_jobs');
    }
};
