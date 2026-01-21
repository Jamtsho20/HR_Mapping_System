<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mrf', function (Blueprint $table) {
            $table->id();

            $table->string('requisition_number')->unique();
            $table->date('date_of_requisition');

            $table->unsignedBigInteger('mas_function_id');
            $table->unsignedBigInteger('mas_department_id');
            $table->unsignedBigInteger('mas_section_id');
            $table->unsignedBigInteger('designation_id');
            $table->unsignedBigInteger('employment_type_id')->nullable();
            $table->unsignedBigInteger('mas_grade_step_id')->nullable();

            $table->string('location')->nullable();
            $table->string('experience')->nullable();

            $table->integer('vacancies');
            $table->enum('mrf_type', ['new', 'replacement']);

            $table->text('job_description')->nullable();
            $table->text('reason')->nullable();
            $table->text('remarks')->nullable();

            $table->unsignedBigInteger('requested_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->enum('status', [
                'hod_submitted',
                'hr_approved',
                'admin_approved',
                'rejected'
            ])->default('hod_submitted');

            $table->timestamps();

            // Foreign keys
            $table->foreign('mas_function_id')->references('id')->on('mas_function');
            $table->foreign('mas_department_id')->references('id')->on('mas_departments');
            $table->foreign('mas_section_id')->references('id')->on('mas_sections');
            $table->foreign('designation_id')->references('id')->on('mas_designations');
            $table->foreign('mas_grade_step_id')->references('id')->on('mas_grade_steps');
            $table->foreign('employment_type_id')->references('id')->on('mas_employment_types');
            $table->foreign('requested_by')->references('id')->on('mas_employees');
            $table->foreign('approved_by')->references('id')->on('mas_employees')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mrf');
    }
};
