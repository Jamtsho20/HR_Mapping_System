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
        Schema::create('received_serials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisition_detail_id')->index()->constrained('requisition_details')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('asset_serial_no')->unique()->index()->comment('Unique serial number for each item.');
            $table->string('asset_description')->nullable();
            $table->decimal('amount', 12, 2)->nullable();
            $table->decimal('quantity', 12, 2)->nullable();
            $table->boolean('is_commissioned')->default(0)->comment('1 => commissioned, 0 => not commissioned');
            $table->boolean('is_transfered')->default(0)->comment('1 => commissioned, 0 => not commissioned');
            $table->boolean('is_returned')->default(0)->comment('1 => commissioned, 0 => not commissioned');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_received_detail_serials');
    }
};
