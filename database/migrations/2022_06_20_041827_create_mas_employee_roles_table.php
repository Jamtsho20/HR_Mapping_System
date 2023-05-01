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
        Schema::create('mas_employee_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_employee_id')->index()->constrained();
            $table->foreignId('role_id')->index()->constrained();
            $table->foreignId('created_by')->index()->constrained('mas_employees');
            $table->foreignId('updated_by')->nullable()->index()->constrained('mas_employees');
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
        Schema::dropIfExists('mas_employee_roles');
    }
};
