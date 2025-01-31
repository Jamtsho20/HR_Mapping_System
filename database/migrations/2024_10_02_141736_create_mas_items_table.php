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
            $table->string('item_no');
            $table->string('grn_no');
            $table->string('po_no');
            $table->string('description')->nullable();
            $table->integer('quantity')->default(0);
            $table->string('uom')->nullable();
            // $table->string('item_type')->nullable();
            $table->string('item_category')->index()->nullable();
            $table->string('asset_type')->nullable();
            $table->string('asset_no');
            // $table->string('asset_class')->nullable();
            // $table->boolean('fa_enabled')->comment('1 => enabled, 0 => disabled');
            $table->boolean('status')->comment('1 => active, 0 => inactive');
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
