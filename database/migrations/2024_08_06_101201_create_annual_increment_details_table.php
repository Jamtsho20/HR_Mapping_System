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
        Schema::create('annual_increment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId("annual_increment_id")->index()->constrained();
            $table->foreignId("mas_employee_id")->index()->constrained('mas_employees');
            $table->decimal("amount",16,2);
            $table->text("remarks")->nullable();
            
            $table->tinyInteger("status")->comment("1 for Approved, 0 for Withheld")->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annual_increment_details');
    }
};
