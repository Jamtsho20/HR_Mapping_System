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
        Schema::create('mas_daily_allowances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_grade_id')->index()->constrained()->cascadeOnDelete(); 
            $table->unsignedBigInteger('da_in_country');
            $table->unsignedBigInteger('da_india_capital')->nullable()->comment('india state capital');
            $table->unsignedBigInteger('da_india_non_capital')->nullable(); 
            $table->unsignedBigInteger('da_third_country')->nullable();
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
        Schema::dropIfExists('mas_daily_allowances');
    }
};
