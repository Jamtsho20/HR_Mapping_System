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
        Schema::create('mas_pay_group_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_pay_group_id')->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('mas_employee_group_id')->index()->nullable()->constrained();
            $table->foreignId('mas_grade_id')->index()->nullable()->constrained();
            $table->tinyInteger("calculation_method")->comment("1 for Actual Method, 2 for Division, 3 for Percentage")->index();
            $table->decimal('amount', 12, 2);
            $table->foreignId('created_by')->index()->constrained('mas_employees');
            $table->foreignId('edited_by')->index()->nullable()->constrained('mas_employees');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mas_pay_group_details');
    }
};
