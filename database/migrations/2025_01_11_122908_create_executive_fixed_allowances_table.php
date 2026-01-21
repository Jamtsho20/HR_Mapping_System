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
        Schema::create('executive_fixed_allowances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained('mas_employees')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('pay_head_id')
                ->constrained('mas_pay_heads')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->float('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('executive_fixed_allowances');
    }
};
