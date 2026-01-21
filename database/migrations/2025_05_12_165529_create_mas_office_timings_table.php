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
        Schema::create('mas_office_timings', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('season')->comment('1 => Spring, 2 => Summer, 3 => Autumn, 4 => Winter');
            $table->unsignedTinyInteger('start_month')->comment('1 => JAN, 2 => FEB and so on');
            $table->unsignedTinyInteger('end_month')->comment('1 => JAN, 2 => FEB and so on');
            $table->time('start_time');
            $table->time('lunch_time_from');
            $table->time('lunch_time_to');
            $table->time('end_time');
            $table->boolean('is_special')->default(0);
            $table->foreignId('created_by')->index()->constrained('mas_employees');
            $table->foreignId('updated_by')->index()->nullable()->constrained('mas_employees');
            // $table->string('start_meridiem', 30)->nullable()->comment('AM/PM');
            // $table->string('end_meridiem', 30)->nullable()->comment('AM/PM');

            $table->timestamps(); // ✅ Correct
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mas_office_timings');
    }
};
