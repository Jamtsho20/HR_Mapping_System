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
        Schema::create('commission_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receipt_id')->index()->constrained('good_receipt_applications')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('commission_no')->index();
            $table->date('commission_date')->index();
            $table->json('attachment')->nullable();
            $table->tinyInteger('status')->comment('1 => New, 0 => Cancelled, -1 => rejected, 2 => Verified, 3 => Approved');
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
        Schema::dropIfExists('commission_applications');
    }
};
