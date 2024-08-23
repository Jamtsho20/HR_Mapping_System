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
        Schema::create('business_units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name');
            $table->date('established_on');
            $table->string('tpn_number', 50);
            $table->text('address');
            $table->string('country', 50);
            $table->foreignId('mas_region_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('mas_office_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('postal_code', 50);
            $table->string('email')->nullable();
            $table->integer('phone_number');
            $table->string('contact_person');
            $table->string('contact_email');
            $table->integer('mobile_number');
            $table->smallInteger('financial_year_from');
            $table->smallInteger('financial_year_to');
            $table->smallInteger('calendar_year_from');
            $table->smallInteger('calendar_year_to');
            $table->text('website');
            $table->string('logo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_units');
    }
};
