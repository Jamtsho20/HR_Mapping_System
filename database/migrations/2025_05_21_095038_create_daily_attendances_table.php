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
        Schema::create('daily_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained('employee_attendances')->references('id')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('department_id')->nullable()->constrained('mas_departments')->references('id')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('section_id')->nullable()->constrained('mas_sections')->references('id')->restrictOnDelete()->cascadeOnUpdate();
            $table->integer('day');
            $table->unsignedTinyInteger('status')->default(1)->comment('1 => Submission Pending, 2 => Submitted/Verified, 3 => Finalized/Approved');
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
        Schema::dropIfExists('daily_attendances');
    }
};
