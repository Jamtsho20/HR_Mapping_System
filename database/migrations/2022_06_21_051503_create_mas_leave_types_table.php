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
        Schema::create('mas_leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('applicable_to')->comment('regular=1, probation=0, both=2');
            $table->smallInteger('max_days')->nullable()->comment('no of leave days');
            $table->text('remarks')->nullable();
            $table->boolean('post_to_sap')->default(0);
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
        Schema::dropIfExists('mas_leave_types');
    }
};
