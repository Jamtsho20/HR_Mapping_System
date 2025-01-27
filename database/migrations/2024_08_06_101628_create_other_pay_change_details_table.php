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
        Schema::create('other_pay_change_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId("other_pay_change_id")->index()->constrained();
            $table->foreignId("mas_employee_id")->index()->constrained();
            $table->foreignId("mas_grade_step_id")->index()->nullable()->constrained();
            $table->integer("no_of_increments")->nullable();
            $table->decimal("new_basic_pay",16,2);
            $table->text("remarks")->nullable();
            $table->tinyInteger("status")->comment("1 for Approved, 0 for Withheld")->default(0);
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
        Schema::dropIfExists('other_pay_change_details');
    }
};
