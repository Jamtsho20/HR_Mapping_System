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
        Schema::create('mas_good_receipt_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->index()->constrained('mas_items')->onDelete('cascade')->onUpdate('cascade');
            $table->string('grn_no')->index();
            $table->string('uom');
            $table->integer('received_quantity');
            $table->integer('changed_quantity')->comment('to keep record of initially received quantity for report and audit purpose.');
            $table->boolean('is_active')->default(1)->comment('1 => Active, 0 =>Inactive; make it in-active once received quantity becomes 0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mas_good_receipt_notes');
    }
};
