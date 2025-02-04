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
            $table->foreignId('store_id')->index()->constrained('mas_stores')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('item_no')->unique();
            $table->string('description')->nullable();
            $table->string('item_category')->index()->nullable();
            $table->string('uom');
            $table->integer('current_stock')->comment('Total stocks availaible in store or live stock available in the system.');
            $table->integer('received_quantity');
            $table->integer('changed_quantity')->comment('initially set changed_quantity to received_quantity.');
            $table->boolean('status')->comment('1 => active, 0 => inactive and this it self will act as fa_enabled or disabled'); 
            $table->timestamp('last_synced_at')->nullable()->comment('Tracks last sync with SAP'); // Tracks last sync with SAP
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
