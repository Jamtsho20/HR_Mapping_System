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
        Schema::create('attendance_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_attendance_id')->constrained('daily_attendances')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('employee_id')->constrained('mas_employees')->references('id');
            $table->foreignId('department_id')->index()->nullable()->constrained('mas_departments')->references('id'); // newly added
            $table->foreignId('section_id')->index()->nullable()->constrained('mas_sections')->references('id'); // newly added
            $table->time('check_in_at')->nullable();
            $table->foreignId('attendance_status_id')->index()->constrained('attendance_statuses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->time('check_out_at')->nullable();
            $table->ipAddress('check_in_ip')->nullable();
            $table->ipAddress('check_out_ip')->nullable();
            $table->text('remarks', 500)->nullable(); //newly added column
            $table->foreignId('created_by')->index()->constrained('mas_employees');
            $table->foreignId('updated_by')->index()->nullable()->constrained('mas_employees');
            //if this required uncomment this two column
            $table->foreignId('updated_by_supervisor')->index()->nullable()->constrained('mas_employees'); // newly added
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_details');
    }
};
