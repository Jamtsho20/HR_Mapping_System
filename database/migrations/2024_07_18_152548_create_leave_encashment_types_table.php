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
        Schema::create('leave_encashment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string("code",50)->index();
            $table->boolean('status')->default(1);
            $table->boolean('post_to_sap')->default(0);
            $table->text('description')->nullable();
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
        Schema::dropIfExists('leave_encashment_types');
    }
};
