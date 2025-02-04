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
        Schema::create('grn_item_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->index()->constrained('mas_stores')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('item_id')->index()->constrained('mas_items')->onDelete('cascade')->onUpdate('cascade');
            $table->string('grn_no')->index();
            $table->string('uom');
            $table->integer('current_stock')->default(0)->comment('Total stocks availaible in store or live stock available in the system (CStock + RQty - CQty).');
            $table->integer('received_quantity')->default(0);
            $table->integer('changed_quantity')->default(0)->comment('to keep record of initially received quantity for report and audit purpose.');
            $table->boolean('status')->default(1)->comment('1 => Active, 0 =>Inactive; make it in-active once changed quantity becomes 0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grn_item_mappings');
    }
};
