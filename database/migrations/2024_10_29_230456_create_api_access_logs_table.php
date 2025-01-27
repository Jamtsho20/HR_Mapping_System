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
        Schema::create('api_access_logs', function (Blueprint $table) {
            $table->id();
            $table->string('consumer')->nullable()->index();
            $table->string('bearer_token')->nullable();
            $table->foreignId('mas_employee_id')->nullable()->constrained('mas_employees')->onDelete('cascade');
            $table->string('method');
            $table->text('url');
            $table->string('ip_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_access_logs');
    }
};
