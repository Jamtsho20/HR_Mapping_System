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
        Schema::create('training_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_application_id')->constrained('training_applications')->restrictOnDelete()->cascadeOnUpdate();
            $table->text('training_provider');
            $table->text('course');
            $table->text('location');
            $table->text('duration');
            $table->text('fee_per_person');
            $table->text('total');
            $table->boolean('best_option')->default(false);
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
        Schema::dropIfExists('training_proposals');
    }
};
