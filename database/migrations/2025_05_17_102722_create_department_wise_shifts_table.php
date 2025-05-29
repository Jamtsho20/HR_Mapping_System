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
        Schema::create('department_wise_shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('type_id')->index()->constrained('mas_shift_types')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('department_id')->index()->constrained('mas_departments')->cascadeOnDelete()->cascadeOnUpdate();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            // $table->string('start_meridiem', 30)->nullable()->comment('AM/PM');
            // $table->string('end_meridiem', 30)->nullable()->comment('AM/PM');
            $table->boolean('status')->comment('1 => Active, 0 => Inactive');
            $table->foreignId('created_by')->index()->constrained('mas_employees');
            $table->foreignId('updated_by')->index()->nullable()->constrained('mas_employees');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_wise_shifts');
    }
};
