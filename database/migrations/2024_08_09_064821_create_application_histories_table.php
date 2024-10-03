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
        Schema::create('application_histories', function (Blueprint $table) {
            $table->id();
            $table->morphs('application');
            $table->string('level');
            $table->tinyInteger('status')->comment('-1 => Rejected, 0 => cancelled/withdrawn, 1 => New, 2 => Approved');
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->index()->constrained('mas_employees')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('approved_by')->nullable()->index()->constrained('mas_employees')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('rejected_by')->nullable()->index()->constrained('mas_employees')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('cancelled_by')->nullable()->index()->constrained('mas_employees')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->index()->constrained('mas_employees')->cascadeOnDelete()->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_histories');
    }
};
