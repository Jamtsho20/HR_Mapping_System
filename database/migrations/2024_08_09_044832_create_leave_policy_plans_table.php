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
        Schema::create('leave_policy_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_leave_policy_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('attachment_required')->default(0)->comment('0 => not required while applying leave, 1 => required');
            $table->unsignedTinyInteger('gender')->comment('1 => Male, 2 => Female, 3 => All');
            $table->unsignedTinyInteger('leave_year')->comment('1 => Calendar Year, 2 => Financial Year');
            $table->unsignedTinyInteger('credit_frequency')->comment('1 => monthly, 2 => yearly');
            $table->unsignedTinyInteger('credit')->comment('1 => Start of period, 2 => end of period; determines when the leave will be credited.');
            $table->string('leave_limits')->nullable()->comment('1 => include public holidays, 2 => can be clubbed with CL, 3 => include weekends, 4 => can be half day 5 => can be clubbed with EL');
            $table->string('can_avail_in')->nullable()->comment('store mas_employment_type_id`s in array.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_policy_plans');
    }
};
