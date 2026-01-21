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
        Schema::create('mas_grade_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_grade_id')->index()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->integer('starting_salary')->default(0);
            $table->integer('increment')->default(0);
            $table->integer('mid_salary')->default(0);
            $table->integer('increment')->default(0);
            $table->integer('ending_salary')->default(0);
            $table->foreignId('created_by')->index()->constrained('mas_employees');
            $table->foreignId('updated_by')->index()->nullable()->constrained('mas_employees');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mas_grade_steps');
    }
};
