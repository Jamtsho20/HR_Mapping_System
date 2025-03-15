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
        Schema::create('commission_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commission_id')->index()->constrained('asset_commission_applications')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('received_detail_serial_id')->index()->constrained('goods_received_detail_serials')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('date_placed_in_service');
            $table->foreignId('dzongkhag_id')->index()->nullable()->constrained('mas_dzongkhags');
            $table->foreignId('office_id')->index()->nullable()->constrained('mas_offices')->comment('for now no need to make use of this only for future purpose');
            $table->foreignId('site_id')->index()->nullable()->constrained('mas_sites');
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_details');
    }
};
