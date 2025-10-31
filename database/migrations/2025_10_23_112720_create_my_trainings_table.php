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
        Schema::create('my_trainings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained('mas_training_types')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('training_nature_id')->constrained('mas_training_natures')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('funding_type_id')->constrained('mas_training_funding_types')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('title');
            $table->foreignId('country_id')->constrained('mas_countries')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('dzongkhag_id')->nullable()->constrained('mas_dzongkhags')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('location')->nullable()->comment('if ex-country fill up these and can be filled up for in-country as well');
            $table->string('institute')->comment('training provider or institute');
            $table->date('start_date');
            $table->date('end_date');
            $table->json('attachment')->nullable()->comment('multiple attachment will be stored');
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
        Schema::dropIfExists('my_trainings');
    }
};
