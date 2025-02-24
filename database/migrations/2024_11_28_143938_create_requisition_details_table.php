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
            $table->string('grn_no')->nullable()->comment('goods receipt number (actually this number can be easily retrieved using grn_item_mapping_id).');
            $table->string('item_description')->nullable();
            $table->string('uom')->nullable();
            $table->string('store_id')->index()->constrained('mas_stores');
            // $table->decimal('stock_status')->comment('quantity availaible in warehouse');
            $table->integer('quantity_required')->default(0);
            $table->string('dzongkhag');
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
