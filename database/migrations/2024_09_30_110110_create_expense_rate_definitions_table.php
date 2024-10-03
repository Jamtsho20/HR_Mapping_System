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
        Schema::create('expense_rate_definitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_expense_policy_id')->index()->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('attachment_required')->default(0)->comment('1 => required, 0 => not required');
            $table->unsignedSmallInteger('travel_type')->nullable()->comment('eg: 1 => Domestic');
            $table->unsignedSmallInteger('rate_currency')->nullable()->comment('eg: 1 => single currency');
            $table->unsignedSmallInteger('currency')->nullable()->comment('eg: 1 => Nu., 2 => INR');
            $table->unsignedTinyInteger('rate_limit')->nullable()->comment('eg: 1 => Daily, 2 => Monthly, 3 => Yearly');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_rate_definitions');
    }
};
