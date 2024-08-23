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
        Schema::create('mas_departments', function (Blueprint $table) {
            $table->id();
            $table->string('short_name')->unique();
            $table->string('name')->unique();
            $table->foreignId('mas_employee_id')->index()->nullable()->constrained('mas_employees')->cascadeOnUpdate()->restrictOnDelete()->comment('HOD');
            $table->boolean('status')->default(1)->comment('0->inactive, 1->active');
            $table->foreignId('created_by')->index()->constrained('mas_employees');
            $table->foreignId('updated_by')->index()->nullable()->constrained('mas_employees');
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
        Schema::dropIfExists('mas_departments');
    }
};
