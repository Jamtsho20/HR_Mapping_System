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
        Schema::create('mas_employee_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('description', 500)->nullable();
            $table->boolean('status')->default(1)->comment('1 => active, 0 => in-active');
            $table->foreignId('created_by')->index()->constrained('mas_employees');
            $table->foreignId('updated_by')->index()->constrained('mas_employees');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mas_employee_groups');
    }
};
