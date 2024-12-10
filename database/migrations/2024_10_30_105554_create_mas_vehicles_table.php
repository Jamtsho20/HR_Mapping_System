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
            $table->string('vehicle_no')->index();
            $table->string('vehicle_type')->index();
            $table->string('location');
            $table->foreignId('department_id')->constrained('mas_department_id')->references('id')->restrictOnDelete()->cascadeOnUpdate();
            $table->boolean('is_active')->default(1)->comment('1 => vehicle is operable, 0 => vehicle is in-operable');
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
