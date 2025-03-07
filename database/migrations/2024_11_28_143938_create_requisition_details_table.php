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
        Schema::create('requisition_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisition_id')->index()->constrained('requisition_applications')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('grn_item_mapping_id')->index()->constrained('grn_item_mappings')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('item_mapping_detail_id')->index()->constrained('item_mapping_details')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('requested_quantity')->default(0);
            $table->integer('approved_quantity')->nullable();
            $table->foreignId('dzongkhag_id')->index()->nullable()->constrained('mas_dzongkhags');
            // $table->string('site_name');
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
        Schema::dropIfExists('requisition_details');
    }
};
