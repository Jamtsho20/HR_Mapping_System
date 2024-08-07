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
        Schema::create('mas_employee_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_employee_id')->index()->constrained()->cascadeOnDelete();
            $table->string('employment_contract')->comment('employment contract documents path');
            $table->string('non_disclosure_aggrement')->comment('Non disclosure aggrerement docs path');
            $table->string('job_responsibilities')->comment('job responsibilities doc path');
            $table->string('other')->comment('Other relevant doc path and cast to array');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mas_employee_documents');
    }
};
