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
        Schema::create('item_mapping_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mapping_id')->index()->constrained('grn_item_mappings')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('code', 50)->index()->comment('item code corresponding to grn_no');
            $table->string('description')->index()->nullable()->comment('description of grn items corresponding to grn_no.');
            $table->integer('received_quantity')->default(0)->comment('quantity received against above code.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_mapping_details');
    }
};
