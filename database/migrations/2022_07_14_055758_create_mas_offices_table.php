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
        Schema::create('mas_offices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('mas_dzongkhag_id')->index()->constrained()->restrictOnDelete()->cascadeOnUpdate();
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('mas_offices');
    }
};
