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
        Schema::create('mas_employee_group_maps', function (Blueprint $table) {
            $table->id();
            $table->foreignId("mas_employee_id")->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId("mas_employee_group_id")->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId("created_by")->index()->constrained('mas_employees');
            $table->foreignId("updated_by")->index()->nullable()->constrained('mas_employees');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mas_employee_group_maps');
    }
};
