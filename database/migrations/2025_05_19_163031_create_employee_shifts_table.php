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
        Schema::create('employee_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_employee_id')->index()->constrained('mas_employees')->cascadeOnUpdate()->restrictOnDelete();
            // $table->foreignId('department_shift_id')->index()->constrained('department_wise_shifts')->cascadeOnUpdate()->restrictOnDelete();
            $table->json('full_shift_days')->nullable()->comment('Array of full shift days like ["Saturday", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Satuarday"]');
            $table->json('morning_shift_days')->nullable()->comment('Array of morning shift days like ["Saturday", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Satuarday"]');
            $table->json('evening_shift_days')->nullable()->comment('Array of evening shift days like ["Saturday", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Satuarday"]');
            $table->json('night_shift_days')->nullable()->comment('Array of night shift days like ["Saturday", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Satuarday"]');
            $table->json('off_days')->nullable()->comment('Array of weekly off days like ["Saturday", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Satuarday"]');
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
        Schema::dropIfExists('employee_shifts');
    }
    
};
