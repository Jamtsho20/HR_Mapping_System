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
        Schema::create('system_hierarchy_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_hierarchy_id')->constrained()->cascadeOnDelete();
            $table->string('level');
            $table->foreignId('approving_authority_id')->index()->constrained('approving_authorities')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('mas_employee_id')->index()->nullable()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('sequence');
            $table->boolean('status')->default(1);
            $table->foreignId('created_by')->index()->constrained('mas_employees');
            $table->foreignId('updated_by')->index()->nullable()->constrained('mas_employees');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_hierarchy_levels');
    }
};
