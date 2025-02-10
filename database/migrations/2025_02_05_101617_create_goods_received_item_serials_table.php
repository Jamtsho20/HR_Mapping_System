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
        Schema::create('goods_received_item_serials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goods_received_detail_id')->index()->constrained('goods_received_details')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('serial_no')->index()->comment('Unique serial number for each item.'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_received_item_serials');
    }
};
