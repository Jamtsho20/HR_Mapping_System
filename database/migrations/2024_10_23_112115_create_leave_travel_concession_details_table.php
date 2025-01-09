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
        Schema::create('leave_travel_concession_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId("ltc_id")->index()->constrained('leave_travel_concessions')->references('id');
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
        Schema::dropIfExists('leave_travel_concession_details');
    }
};
