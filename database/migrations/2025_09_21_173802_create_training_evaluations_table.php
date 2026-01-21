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
            $table->foreignId('evaluation_type_id')->constrained('mas_training_evaluation_types')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('training_evaluations')->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('is_floated_to_trainees')->default(false)->comment('Indicates if question is floated/visible to trainees');
            $table->string('title')->nullable()->comment('Section title (for parent evaluation)');
            $table->text('question')->nullable()->comment('Sub-question text');
            $table->string('question_type')->nullable();
            $table->unsignedInteger('sequence')->index();
            $table->foreignId('created_by')->index()->constrained('mas_employees');
            $table->foreignId('updated_by')->index()->nullable()->constrained('mas_employees');
            $table->timestamps();
        });


        Schema::create('training_evaluation_options', function (Blueprint $table) {
            $table->id();

            $table->foreignId('evaluation_id')->constrained('training_evaluations')->cascadeOnUpdate()->cascadeOnDelete();

            $table->string('option_text')->comment('Option text');
            $table->unsignedInteger('sequence')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_evaluation_options');
        Schema::dropIfExists('training_evaluations');
    }
};
