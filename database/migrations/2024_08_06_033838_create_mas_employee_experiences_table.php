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
        Schema::create('mas_employee_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_employee_id')->index()->constrained()->cascadeOnDelete();
            $table->string('organization')->nullable();
            $table->string('place')->nullable();
            $table->string('designation')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('description', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mas_employee_experiences');
    }
};
