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
        Schema::create('leave_policy_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_policy_plan_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('mas_grade_id')->constrained()->restrictOnDelete()->cascadeOnUpdate();
            $table->unsignedTinyInteger('uom')->comment('1 => day, 2 => month, 3 => year; unit of measurement(uom)');
            $table->unsignedInteger('duration');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_loss_of_pay')->comment('whether applying this leave affects pay or not.');
            $table->foreignId('mas_employment_type_id')->constrained()->restrictOnDelete()->cascadeOnUpdate();
            $table->boolean('status')->default('1')->comment('0 => inactive, 1 => active');

            $table->timestamps();

            $table->comment('for each grade_id store entries independently');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_policy_rules');
    }
};
