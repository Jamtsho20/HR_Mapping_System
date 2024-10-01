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
        Schema::create('expense_policy_enforcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_expense_policy_id')->index()->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('prevent_report_submission')->nullable()->comment('0 => false, 1 => true');
            $table->boolean('display_warning_to_user')->nullable()->comment('0 => false, 1 => true');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_policy_enforcements');
    }
};
