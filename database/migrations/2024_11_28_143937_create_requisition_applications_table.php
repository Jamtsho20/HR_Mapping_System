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
            $table->foreignId('type_id')->index()->constrained('mas_requisition_types')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('transaction_no')->unique()->index();
            $table->date('transaction_date')->index();
            // $table->string('asset_type')->index()->nullable()->comment('if required in future, can make use of it');
            $table->date('need_by_date');
            // $table->integer('total_quantity_required')->default(0);
            $table->foreignId('requested_by')->index()->nullable()->constrained('mas_employees');
            $table->tinyInteger('status')->index()->comment('-1 => rejected, 1 => new, 2 => verified, 3 => approved');
            $table->string('doc_no')->index()->nullable();
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
