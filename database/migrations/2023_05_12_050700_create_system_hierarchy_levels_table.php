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
            $table->string('value');
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger('status');
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
