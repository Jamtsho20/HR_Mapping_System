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
        Schema::create('leave_policy_year_end_processings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_leave_policy_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('allow_carryover')->default(0);
            $table->unsignedInteger('carryover_limit')->nullable();
            $table->boolean('pay_at_year_end')->default(0);
            $table->unsignedInteger('min_balance_required')->default(0)->comment('Min. Balance Need To be Maintained');
            $table->unsignedInteger('min_encashment_per_year')->default(0)->comment('Maximum Encashment Per Year');
            $table->boolean('carry_forward_to_el')->default(0);
            $table->unsignedInteger('carry_forward_limit')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_policy_year_end_processings');
    }
};
