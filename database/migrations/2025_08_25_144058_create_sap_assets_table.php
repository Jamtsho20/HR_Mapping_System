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
        Schema::create('sap_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('mas_items')->cascadeOnDelete();
            $table->string('serial_number')->unique()->nullable();
            $table->string('asset_number')->unique();
            $table->string('uom', 20)->nullable();
            $table->string('grn_number')->nullable();
            $table->string('item_description');
            $table->string('category');
            $table->integer('quantity');
            $table->decimal('amount', 16, 2);
            $table->date('capitalization_date');
            $table->date('end_date');
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
        Schema::dropIfExists('sap_assets');
    }
};
