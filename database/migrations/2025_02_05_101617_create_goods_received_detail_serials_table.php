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
        Schema::create('goods_received_detail_serials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goods_received_detail_id')->index()->constrained('goods_received_details')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('asset_serial_no')->unique()->index()->comment('Unique serial number for each item.'); 
            $table->string('asset_description')->nullable()->comment('if it is null make use of item description');
            $table->boolean('is_commissioned')->default(0)->comment('1 => commissioned, 0 => not commissioned');
            // $table->string('asset_class')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_received_detail_serials');
    }
};
