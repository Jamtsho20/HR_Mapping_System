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
        Schema::create('mas_condition_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_approval_head_id')->constrained();
            $table->string('name', 50);
            $table->string('label', 50)->nullable();
            $table->boolean('has_employee_field')->nullable();
            $table->boolean('has_role_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mas_condition_fields');
    }
};
