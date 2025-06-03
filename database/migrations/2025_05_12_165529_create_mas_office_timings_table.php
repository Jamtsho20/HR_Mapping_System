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
            // $table->enum('start_month', ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC']);
            // $table->enum('end_month', ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC']);
            $table->unsignedTinyInteger('start_month')->comment('1 =>JAN, 2 => FEB and so on');
            $table->unsignedTinyInteger('end_month')->comment('1 =>JAN, 2 => FEB and so on');
            $table->time('start_time'); // start time
            $table->time('lunch_time_from');
            $table->time('lunch_time_to');
            $table->time('end_time');// end time
            // $table->string('start_meridiem', 30)->nullable()->comment('AM/PM');
            // $table->string('end_meridiem', 30)->nullable()->comment('AM/PM');
            $table->timestamps();
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
