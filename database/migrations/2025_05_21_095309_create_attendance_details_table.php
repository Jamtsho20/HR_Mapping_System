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
            $table->time('check_in_at')->nullable();
            $table->foreignId('attendance_status_id')->index()->constrained('attendance_statuses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->time('check_out_at')->nullable();
            $table->ipAddress('check_in_ip')->nullable();
            $table->ipAddress('check_out_ip')->nullable();
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
        Schema::dropIfExists('attendance_details');
    }
};
