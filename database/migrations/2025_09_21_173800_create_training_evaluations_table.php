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
        Schema::create('training_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_list_id')->constrained('mas_training_lists')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('evaluation_type_id')->constrained('mas_training_evaluation_types')->cascadeOnUpdate()->restrictOnDelete();
            $table->text('question');
            $table->unsignedInteger('sequence')->index();
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
        Schema::dropIfExists('training_evaluations');
    }
};
