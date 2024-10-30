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
        Schema::create('expense_fuel_claim_applications', function (Blueprint $table) {
            $table->id();
            $table->string('location');
            $table->date('date')->index();
            $table->foreignId('vehicle_id')->index()->constrained('mas_vehicles')->restrictOnDelete()->cascadeOnUpdate();
            $table->json('attachment')->nullable()->comment('multiple attachment & casted as array to store path');
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
        Schema::dropIfExists('expense_fuel_claim_applications');
    }
};
