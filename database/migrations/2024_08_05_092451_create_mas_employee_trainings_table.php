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
        Schema::create('mas_employee_trainings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_employee_id')->index()->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('title', 100)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedInteger('duration')->nullable();
            $table->string('location', 100)->nullable();
            $table->string('description', 500)->nullable();
            $table->string('certificate')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mas_employee_trainings');
    }
};
