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
        Schema::create('asset_return_applications', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_no')->index();
            $table->foreignId('type_id')->index()->constrained('mas_return_types')->restrictOnDelete()->cascadeOnUpdate();
            $table->date('transaction_date')->index();
            // $table->foreignId('requisition_detail_id')->index()->constrained('requisition_details')->comment('do transfer against goods received detail as it has to be done against each GRN');
            $table->json('attachment')->nullable();
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
        Schema::dropIfExists('asset_return_applications');
    }
};
