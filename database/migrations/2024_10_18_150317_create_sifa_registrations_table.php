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
        Schema::create('sifa_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_employee_id')->index()->nullable()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('sifa_type_id')->index()->nullable()->constrained('mas_sifa_types')->cascadeOnUpdate()->restrictOnDelete();
            $table->boolean('is_registered')->default(3);
            $table->boolean('status')->default(1);
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('sifa_registrations');
    }
};
