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
            $table->string('purchase_order_no')->comment('PO');
            $table->string('item_description');
            $table->string('uom');
            $table->string('store');
            // $table->tinyInteger('stock_status')->comment('1,2,3,4');
            $table->decimal('quantitiy_required', 5, 2);
            $table->string('dzongkhag');
            $table->string('site_name');
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
