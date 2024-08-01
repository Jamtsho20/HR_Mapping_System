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
        Schema::create('mas_approval_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_approval_rule_id')->constrained();
            $table->string('condition', 50)->nullable();
            $table->foreignId('mas_condition_field_id')->constrained();
            $table->string('operator', 100);
            $table->string('value', 100)->nullable()->comment('value when seletd field is not related to user.');
            $table->foreignId('mas_employee_id')->nullable()->constrained('mas_employees')->comment('Employee Id when selected field is related to user from field column.');
            $table->string('formula_display', 500)->nullable();
            $table->foreignId('system_hierarchy_id')->nullable()->constrained();
            $table->string('max_level', 50)->nullable();
            $table->unsignedTinyInteger('auto_approval')->default(0);
            $table->unsignedTinyInteger('is_single_user')->default(0);
            $table->foreignId('appvl_employee_id')->nullable()->constrained('mas_employees')->comment('Employee Id for approval, when approval type is single user.');
            $table->string('fyi_level', 50)->nullable();
            $table->string('email')->nullable();
            $table->foreignId('fyi_employee_id')->nullable()->constrained('mas_employees');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mas_approval_conditions');
    }
};
