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
        Schema::create('mas_vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('vihicle_no')->index();
            $table->unsignedTinyInteger('vehicle_type')->index()->comment('1 => Light, 2 => Medium, 3 => Heavy, 4 => Two Wheeler');
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
        Schema::dropIfExists('mas_vehicles');
    }
};
