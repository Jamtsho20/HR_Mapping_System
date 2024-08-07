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
