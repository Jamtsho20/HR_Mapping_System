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
            $table->string('asset_no');
            $table->string('category');
            $table->text('item_description');
            $table->string('asset_key');
            $table->string('asset_type');
            $table->string('units');
            $table->string('property_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_transfer_details');
    }
};
