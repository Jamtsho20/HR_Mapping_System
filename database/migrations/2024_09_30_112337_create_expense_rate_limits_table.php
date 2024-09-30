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
        Schema::create('expense_rate_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_rate_definition_id')->index()->constrained('expense_rate_definitions')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('mas_grade_step_id')->index()->constrained()->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('mas_region_id')->index()->constrained()->restrictOnDelete()->cascadeOnUpdate();
            $table->integer('limit_amount');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('status')->default(1)->comment('1 => Active, 0 => In Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_rate_limits');
    }
};
