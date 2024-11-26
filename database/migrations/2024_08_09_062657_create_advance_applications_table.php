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
            $table->foreignId('mas_employee_id')->nullable()->constrained('mas_employees')->restrictOnDelete()->cascadeOnUpdate()->comment('if advance is applied on behalf of someone');
            $table->foreignId('advance_type_id')->constrained('mas_advance_types')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('travel_authorization_id')->nullable()->constrained('travel_authorization_applications')->cascadeOnDelete()->cascadeOnUpdate()->restrictOnDelete()->comment('required only if advance_type is dsa advance');
            $table->date('advance_settlement_date')->nullable();
            $table->decimal("amount", 12, 2)->nullable();
            $table->string('attachment')->nullable();
            $table->decimal("total_amount", 12, 2)->nullable();
            $table->unsignedInteger('no_of_emi')->comment("3 => 3 months, 6 => 6 months, 9 => 9 months, 12 => 12 months")->nullable();
            $table->decimal("monthly_emi_amount", 12, 2)->nullable();
            $table->date('deduction_from_period')->nullable();
            $table->string('item_type')->nullable();
            $table->text('remark')->nullable();
            $table->decimal('interest_rate', 5, 2)->nullable();
            $table->tinyInteger('status')->default(1)->comment('-1 => Rejected, 0 => cancelled/withdrawn, 1 => New/Submitted, 2 => Verified, 3 => approved, 4 => disbursed/paid');
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
