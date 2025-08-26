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
        Schema::create('mas_assets', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique()->nullable()->index();
            $table->foreignId('current_employee_id')->nullable()->constrained('mas_employees');
            $table->foreignId('item_id')->index()->nullable()->constrained('mas_items')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('current_site_id')->nullable()->constrained('mas_sites');
            $table->foreignId('received_serial_id')->unique()->nullable()->constrained('received_serials')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('sap_asset_id')->unique()->nullable()->constrained('sap_assets')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('commission_detail_id')->index()->nullable()->constrained('commission_details')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('asset_transfer_detail_id')->nullable()->constrained('asset_transfer_details')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('return_detail_id')->nullable()->constrained('return_details')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('initial_owner_id')->index()->nullable()->constrained('mas_employees')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('created_by')->index()->constrained('mas_employees');
            $table->foreignId('updated_by')->index()->nullable()->constrained('mas_employees');
            $table->tinyInteger('status')->default(1)->comment('1 = commissioned, 2 = transferred, 3 = returned');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mas_assets');
    }
};
