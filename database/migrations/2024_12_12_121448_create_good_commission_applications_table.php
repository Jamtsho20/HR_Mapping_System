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
        Schema::create('good_commission_applications', function (Blueprint $table) {
            $table->id();
            $table->string('commission_no')->index();
            $table->foreignId('receipt_id')->index()->constrained('good_receipt_applications')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('commission_date')->index();
            $table->tinyInteger('status')->comment('1 => Commissioned, 0 => Not Commissioned');
            $table->string('file')->nullable();
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
        Schema::dropIfExists('good_commission_applications');
    }
};
