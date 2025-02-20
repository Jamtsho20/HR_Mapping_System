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
        Schema::create('asset_commission_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_commission_id')->index()->constrained('asset_commission_applications')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('received_detail_serial_id')->index()->constrained('goods_received_detail_serials')->cascadeOnUpdate()->cascadeOnDelete();
            // $table->string('grn_no')->comment('Goods Receipt Number');
            $table->string('asset_no');
            // $table->string('item_description');
            // $table->string('uom');
            // $table->integer('quantity');
            $table->date('date_placed_in_service');
            $table->string('dzongkhag');
            $table->string('site_name');
            $table->text('remark')->nullable();
            $table->boolean('status')->comment('1 => commissioned, 0 => Not Commisioned');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_commission_details');
    }
};
