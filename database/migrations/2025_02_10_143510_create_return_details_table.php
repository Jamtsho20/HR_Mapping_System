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
        Schema::create('return_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_return_id')->index()->constrained('asset_return_applications')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('mas_asset_id')->index()->constrained('mas_assets')->cascadeOnUpdate()->cascadeOnDelete();
            //$table->unsignedInteger('unit');
            $table->foreignId('dzongkhag_id')->index()->constrained('mas_dzongkhags')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('store_id')->index()->constrained('mas_stores')->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedTinyInteger('condition_code')->default(1)->index()->comment('1 => Beyond Economic Repair, 2 => Absolete, 3 => Working');
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_details');
    }
};
