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
        Schema::create('asset_transfer_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_transfer_id')->index()->constrained('asset_transfer_applications')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('received_serial_id')->index()->constrained('received_serials')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_details');
    }
};
