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
        Schema::create('trainees_training_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_list_id')->nullable()->constrained('trainee_lists')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('my_training_id')->nullable()->constrained('my_trainings')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('document_title');
            $table->json('attachment');
            $table->json('owner_ship')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('trainees_training_materials');
    }
};
