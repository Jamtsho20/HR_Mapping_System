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
        Schema::create('requisition_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisition_type_id')->index()->constrained('mas_requisition_types')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('requisition_no')->index();
            $table->date('requisition_date')->index();
            $table->string('asset_type')->index();
            $table->date('need_by_date');
            $table->foreignId('employee_id')->index()->nullable()->constrained('mas_employees')->cascadeOnUpdate()->restrictOnDelete()->comment('if requisition is done on behalf of someone');
            $table->string('item_category')->index();
            $table->tinyInteger('status')->comment('-1 => rejected, 1 => new, 2 => verified, 3 => approved');
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
        Schema::dropIfExists('requisition_applications');
    }
};
