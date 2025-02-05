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
            $table->string('grn_no')->comment('goods receipt number.');
            $table->string('item_description');
            $table->string('uom');
            $table->string('store');
            $table->decimal('stock_status')->comment('quantity availaible in warehouse');
            $table->integer('quantity_required');
            $table->string('dzongkhag');
            $table->string('site_name');
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
