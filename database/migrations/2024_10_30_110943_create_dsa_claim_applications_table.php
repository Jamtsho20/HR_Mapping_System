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
        Schema::create('dsa_claim_applications', function (Blueprint $table) {
            $table->id();
            $table->string('dsa_claim_no')->index();
            $table->foreignId('advance_application_id')->nullable()->constrained()->restrictOnDelete()->restrictOnUpdate();
            $table->decimal('total_amount', 12, 2);
            $table->decimal('net_payable_amount', 12, 2);
            $table->decimal('balance_amount', 12, 2)->nullable();
            $table->json('attachment')->nullable()->comment('relevant attachment path and casted to array');
            $table->tinyInteger('status')->default(1)->comment('-1 => Rejected, 0 => cancelled/withdrawn, 1 => New, 2 => Approved');

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
        Schema::dropIfExists('dsa_claim_applications');
    }
};
