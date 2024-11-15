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
            $table->string('delimiter')->nullable();
            $table->foreignId('operator_id')->constrained('mas_approval_rule_condition_operators')->references('id');
            $table->string('value', 100)->nullable()->comment('value when selected field is not related to user.');
            $table->foreignId('mas_employee_id')->nullable()->constrained('mas_employees')->comment('Employee Id when selected field is related to user from field column.');
            $table->string('formula_display', 500)->nullable();
            $table->tinyInteger('approval_option')->comment('1 => hierarchical, 2 => single user, 3 => auto approval');
            $table->foreignId('system_hierarchy_id')->nullable()->constrained();
            $table->foreignId('max_level_id')->nullable()->constrained('system_hierarchy_levels')->references('id');
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
