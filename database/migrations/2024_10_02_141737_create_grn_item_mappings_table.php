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

            // $table->string('item_description')->nullable();
            $table->string('grn_no', 50)->index();        
            // $table->string('uom')->nullable();
            $table->timestamp('last_synced_at')->nullable()->comment('Tracks last sync with SAP');
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
