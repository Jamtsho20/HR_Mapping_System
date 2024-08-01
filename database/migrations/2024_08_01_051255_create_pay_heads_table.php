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
        Schema::create('pay_heads', function (Blueprint $table) {
            $table->id();
            $table->string("name",150)->index();
            $table->string("code",50)->index();
            $table->tinyInteger("payhead_type")->comment("1 for Allowance, 2 for Deduction")->index();
            $table->tinyInteger("accounthead_type")->comment("1 for Allowance, 2 for Deduction")->index();
            $table->tinyInteger("calculation_method")->comment("1 for Actual Method, 2 for Division, 3 for Slab Wise, 4 for Group Wise, 5 for Percentage")->index();
            $table->tinyInteger("calculated_on")->comment("1 for Basic Pay, 2 for Gross Pay, 3 for Net Pay, 4 for PIT Net Pay")->index();
            $table->text("formula")->nullable();
            $table->uuid("created_by")->index();
            $table->uuid("edited_by")->index()->nullable();
            $table->uuid("updated_by")->index()->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_heads');
    }
};
