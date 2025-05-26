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
        Schema::create('sifa_contr_historicals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_employee_id')->index()->constrained('mas_employees')->cascadeOnUpdate()->restrictOnDelete();
            $table->date("for_month")->index();
            $table->decimal('sifa_contr', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sifa_contr_historicals');
    }
};
