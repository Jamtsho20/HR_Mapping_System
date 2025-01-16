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
        Schema::create('budget_codes', function (Blueprint $table) {
            $table->id();
            $table->integer('code')->required();
            $table->string('particular')->required();
            $table->unsignedBigInteger('budget_type_id')->required();
            $table->timestamps();
            $table->foreignId('created_by')->index()->constrained('mas_employees');
            $table->foreignId('updated_by')->index()->nullable()->constrained('mas_employees');
            $table->foreign('budget_type_id')->references('id')->on('budget_types')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_codes');
    }
};
