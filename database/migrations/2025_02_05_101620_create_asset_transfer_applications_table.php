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
        Schema::create('asset_transfer_applications', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_no')->index();
            $table->foreignId('type_id')->index()->constrained('mas_transfer_types')->restrictOnDelete()->cascadeOnUpdate();
            $table->date('transfer_date')->index();
            $table->text('reason_of_transfer')->nullable();
            $table->foreignId('from_employee_id')->constrained('mas_employees')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('to_employee_id')->constrained('mas_employees')->restrictOnDelete()->cascadeOnUpdate();
            // $table->string('old_location'); // make use of office / site_id / store_id
            // $table->string('new_location'); // make use of office / site_id / store_id
            $table->foreignId('from_site_id')->index()->constrained('mas_sites')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('to_site_id')->index()->constrained('mas_sites')->restrictOnDelete()->cascadeOnUpdate();
            $table->json('attachment')->nullable();
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
        Schema::dropIfExists('asset_transfer_applications');
    }
};
