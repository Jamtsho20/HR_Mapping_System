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
        Schema::create('transfer_claim_applications', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_claim');
            $table->foreign('transfer_claim') // Defining the foreign key
                ->references('name') // Referencing the 'name' column in the parent table
                ->on('transfer_claims') // Parent table name
                ->onDelete('restrict')
                ->onUpdate('cascade'); // Optional: Define behavior on delete
            $table->string('current_location');
            $table->string('new_location');
            $table->decimal('amount_claimed', 10, 2);
            $table->decimal('distance_travelled', 10, 2)->nullable()->comment('required only if transfer claim is Carriage Charge');
            $table->json('attachment')->nullable()->comment('multiple file path casted to array');
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
        Schema::dropIfExists('transfer_claim_applications');
    }
};
