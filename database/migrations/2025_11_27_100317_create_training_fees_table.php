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
        Schema::create('training_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_application_id')->constrained('training_applications')->restrictOnDelete()->cascadeOnUpdate();
            $table->text('institute');
            $table->text('training_name');
            $table->text('location');
            $table->text('participants');
            $table->text('total_cost');
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
        Schema::dropIfExists('training_fees');
    }
};
