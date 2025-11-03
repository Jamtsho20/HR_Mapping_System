<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_evaluation_employee', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('training_evaluations')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('mas_employees')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_evaluation_employee');
    }
};
