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
        Schema::create('employee_leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_leave_type_id')->index()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('mas_employee_id')->index()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->smallInteger('opening_balance')->default(0);
            $table->smallInteger('current_entitlement')->default(0);
            $table->smallInteger('leaves_availed')->default(0);
            $table->smallInteger('closing_balance')->default(0);
            $table->foreignId('created_by')->index()->constrained('mas_employees');
            $table->foreignId('updated_by')->index()->nullable()->constrained('mas_employees');
            $table->timestamps();

            $table->comment('Records the number of leave days for each employee based on leave type. Accounts for mid-year employee joins and leave eligibility based on employment type.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_leaves');
    }
};
