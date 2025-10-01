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
        Schema::create('training_budget_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_list_id')->constrained('mas_training_lists')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('training_expense_type_id')->constrained('mas_training_expense_types')->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('amount_allocated', 12, 2);
            $table->decimal('by_company', 12, 2)->comment('amount funded by company for particular expense.');
            $table->decimal('by_sponsor', 12, 2)->comment('amount funded by sponsor for particular expense.');
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
        Schema::dropIfExists('training_budget_allocations');
    }
};
