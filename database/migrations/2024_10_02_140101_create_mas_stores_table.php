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
        Schema::create('mas_stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_store_id')->index()->nullable()->constrained('mas_stores')->cascadeOnDelete();
            $table->string('name')->index();
            $table->string('code')->index()->nullable();
            $table->string('country')->default('Bhutan');
            $table->string('dzongkhag')->index()->nullable();
            $table->string('region')->index()->nullable();
            // $table->string('store_location')->nullable();
            $table->string('store_email')->nullable();
            $table->string('store_phone')->nullable();
            // $table->string('contact_person')->nullable();
            // $table->string('contact_email')->nullable();
            // $table->string('contact_number')->nullable();
            $table->foreignId('store_officer')->index()->nullable()->constrained('mas_employees');
            $table->boolean('status')->default(1)->comment('1 => active, 0 => in-active');
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
        Schema::dropIfExists('mas_stores');
    }
};
