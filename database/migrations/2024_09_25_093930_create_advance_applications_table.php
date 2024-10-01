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
        Schema::create('advance_applications', function (Blueprint $table) {
            $table->id();
            $table->string('advance_no');
            $table->date('date');
            $table->tinyInteger('advance_type')->comment("1 for Advance to Staff, 2 for DSA Advance, 3 for Electricity Imprest Advance, 4 for Gadget Emi, 5 for Imprest Advance,6 for Salary Advance, 7 for SIFA Loan")->index();
            $table->tinyInteger("mode_of_travel")->comment("1 for Bike, 2 for Bus, 3 for Car, 4 for Flight, 5 for Train")->index()->nullable();
            $table->string('from_location')->nullable();
            $table->string('to_location')->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->decimal("amount", 12,2)->nullable();
            $table->string('purpose',150)->index()->nullable();
            $table->string('attachment')->nullable();
            $table->decimal('interest_rate', 5, 4)->nullable();
            $table->decimal("total_amount", 12,2)->nullable();
            $table->unsignedSmallInteger('no_of_emi')->comment("1 for 3, 2 for 6, 3 for 9, 4 for 12")->index()->nullable();
            $table->decimal("monthly_emi_amount", 12,2)->nullable();
            $table->date('deduction_from_period')->nullable();
            $table->string('item_type')->nullable();
            $table->foreignId('mas_employee_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('advance_applications');
    }
};
