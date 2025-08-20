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
        Schema::create('mas_asset_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('mas_assets')->cascadeOnDelete();
            $table->foreignId('current_employee_id')->nullable()->constrained('mas_employees');
            $table->foreignId('current_site_id')->nullable()->constrained('mas_sites');
            $table->foreignId('asset_transfer_detail_id')->constrained('asset_transfer_details')->nullable()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('return_detail_id')->constrained('return_details')->nullable()->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('quantity')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::dropIfExists('mas_asset_logs');
    }
};
