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
        Schema::create('trainee_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_application_id')->constrained('training_applications')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('employee_id')->constrained('mas_employees')->restrictOnDelete()->cascadeOnUpdate();
            $table->boolean('is_availaible_for_training')->default(1)->comment('sometimes during the time of training may be emp cannot join the training.');
            $table->json('certificate')->comment('multiple certificate to be accepted.');//user will update not admin so keep nullable 
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
        Schema::dropIfExists('trainee_lists');
    }
};
