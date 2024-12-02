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
        Schema::create('mas_pay_heads', function (Blueprint $table) {
            $table->id();
            $table->string("name",150)->index();
            $table->string("code",50)->index();
            $table->string("general_ledger_code", 50)->unique()->index();
            $table->tinyInteger("payhead_type")->comment("1 for Allowance, 2 for Deduction")->index();
            $table->tinyInteger("calculation_method")->comment("1 for Actual Amount, 2 for Division, 3 for Slab Wise, 4 for Group Wise, 5 for Percentage, 6 for By Formula, 7 for Employee Wise")->index();
            $table->tinyInteger("calculated_on")->nullable()->comment("1 for Basic Pay, 2 for Gross Pay, 3 for Net Pay, 4 for PIT Net Pay, 5 for By Formula, 6 for Pay Scale Base Pay")->index();
            $table->decimal("amount", 12,2)->nullable();
            $table->foreignId("mas_pay_slab_id")->index()->nullable()->constrained();
            $table->foreignId("mas_pay_group_id")->index()->nullable()->constrained();
            $table->foreignId("account_head_id")->index()->constrained('mas_acc_account_heads');
            $table->text("formula")->nullable();
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
        Schema::dropIfExists('mas_pay_heads');
    }
};
