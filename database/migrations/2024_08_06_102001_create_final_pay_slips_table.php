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
        Schema::create('final_pay_slips', function (Blueprint $table) {
            $table->id();
            $table->date("for_month");
            $table->foreignId('mas_employee_id')->index()->constrained();
            $table->json("details");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('final_pay_slips');
    }
};
