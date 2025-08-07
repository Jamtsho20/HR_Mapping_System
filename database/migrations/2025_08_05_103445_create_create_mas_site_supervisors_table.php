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
        Schema::create('mas_site_supervisors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('mas_employees');
            $table->foreignId('dzongkhag_id')->constrained('mas_dzongkhags');
            $table->foreignId('created_by')->index()->constrained('mas_employees');
            $table->foreignId('updated_by')->index()->nullable()->constrained('mas_employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('create_mas_site_supervisors');
    }
};
