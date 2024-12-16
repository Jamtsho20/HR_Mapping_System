<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mas_expense_types', function (Blueprint $table) {
            $table->id();
            $table->string("code",50)->index();
            $table->foreignId('type_id')->index()->nullable()->constrained('mas_expense_types')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->foreignId('created_by')->index()->constrained('mas_employees');
            $table->foreignId('updated_by')->index()->nullable()->constrained('mas_employees');
            $table->boolean('status')->default(1)->comment('1=active,0=inactive');
            $table->boolean('post_to_sap')->default(0)->comment('1=active,0=inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mas_expense_types');
    }
};
