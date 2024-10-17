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
            $table->string('advance_no')->index();
            $table->date('date')->index();
            $table->foreignId('advance_type_id')->constrained('mas_advance_types')->cascadeOnDelete()->cascadeOnUpdate();
            $table->tinyInteger("mode_of_travel")->comment("1 for Bike, 2 for Bus, 3 for Car, 4 for Flight, 5 for Train")->index()->nullable();
            $table->string('from_location')->nullable();
            $table->string('to_location')->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->decimal("amount", 12, 2)->nullable();
            $table->string('attachment')->nullable();
            // $table->decimal('interest_rate', 5, 2)->nullable();
            $table->decimal("total_amount", 12, 2)->nullable();
            $table->unsignedInteger('no_of_emi')->comment("3 => 3 months, 6 => 6 months, 9 => 9 months, 12 => 12 months")->nullable();
            $table->decimal("monthly_emi_amount", 12, 2)->nullable();
            $table->date('deduction_from_period')->nullable();
            $table->string('item_type')->nullable();
            $table->text('remark')->nullable();
            $table->tinyInteger('status')->default(1)->comment('-1 => Rejected, 0 => cancelled/withdrawn, 1 => New, 2 => Approved');
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
