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
        Schema::create('mas_employee_present_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_employee_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignId('mas_dzongkhag_id')->index()->constrained();
            $table->foreignId('mas_gewog_id')->index()->constrained();
            $table->string('city', 50);
            $table->string('postal_code', 30);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mas_employee_present_addresses');
    }
};
