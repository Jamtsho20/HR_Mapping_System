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
        Schema::create('good_receipt_application_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('good_receipt_id')->index()->constrained('good_receipt_applications')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('purchase_order_no')->comment('PO');
            $table->string('item_description');
            $table->string('uom');
            $table->string('store');
            $table->decimal('stock_status')->comment('quantity availaible in warehouse');
            $table->decimal('receipt_quantity', 5, 2);
            $table->string('dzongkhag');
            $table->string('site_name');
            $table->text('remark')->nullable();
            $table->decimal('balance', 5,2);
            $table->boolean('status')->comment('1 => Not commissioned, 0 =>Commisioned');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('good_receipt_application_details');
    }
};
