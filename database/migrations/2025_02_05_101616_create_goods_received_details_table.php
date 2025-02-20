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
            $table->string('grn_no')->nullable();
            $table->string('uom')->nullable();
            $table->string('item_description')->nullable();
            $table->string('asset_type')->nullable()->comment('eg. consumable, fixed asset');
            $table->string('asset_class')->nullable();
            $table->integer('requested_quantity')->default(0);
            $table->integer('received_quantity')->default(0);
            $table->integer('comissioned_quantity')->default(0)->comment('quantity that has been commissioned (put to use)');
            $table->unsignedTinyInteger('commissioned_status')->default(0)->comment('1 => Partial Comissioned, 0 => Not Commissioned, 2 => Comissioned Completed (while displaying at frontend only display with status 0 and 1)');
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
