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
        Schema::create('mas_employee_qualifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_employee_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignId('mas_qualification_id')->index()->constrained();
            $table->string('school')->nullable();
            $table->string('subject')->nullable();
            $table->string('completion_year')->nullable();
            $table->decimal('aggregate_score')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mas_employee_qualifications');
    }
};
