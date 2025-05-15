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
        Schema::create('mas_items', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('store_id')->index()->constrained('mas_stores')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('item_no')->unique()->index();
            $table->string('previous_item_no')->unique();
            $table->string('item_description');
            // $table->string('item_type')->index()->nullable();
            $table->string('item_group')->index()->nullable()->comment('consumable & fixed asset');
            $table->string('uom');
            // $table->integer('current_stock')->comment('Total stocks availaible in store or live stock available in the system (CStock + RQty - CQty).');
            // $table->integer('received_quantity')->comment('as soon as if goods is received under same item_no in goods_receipt_note update RQTY');
            // $table->integer('changed_quantity')->comment('initially set changed_quantity to received_quantity and if there is update in RQty add to existing.');
            $table->boolean('is_fixed_asset')->default(1)->comment('1 => fixed asset, 0 => other type of asset (no need to comission)');
            $table->boolean('status')->comment('1 => active, 0 => inactive and this it self will act as fa_enabled or disabled');
            // $table->timestamp('last_synced_at')->nullable()->comment('Tracks last sync with SAP'); // Tracks last sync with SAP
            $table->foreignId('created_by')->index()->constrained('mas_employees');
            $table->foreignId('updated_by')->index()->nullable()->constrained('mas_employees');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mas_items');
    }
};
