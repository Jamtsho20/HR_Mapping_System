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
        Schema::create('sifa_dependents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sifa_registration_id')->index()->constrained('sifa_registrations')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('dependent_name');
            $table->string('relation_with_employee');
            $table->string('cid_number');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sifa_dependents');
    }
};
