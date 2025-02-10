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
        Schema::create('goods_received_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goods_received_by_user_id')->index()->constrained('mas_goods_received_by_users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('req_detail_id')->index()->constrained('requisition_details');
            // $table->string('uom')->nullable();
            // $table->string('item_description')->nullable();
            $table->integer('requested_quantity');
            $table->integer('received_quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_received_details');
    }
};
