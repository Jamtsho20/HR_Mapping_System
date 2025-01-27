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
        Schema::create('sifa_and_retirement_nominations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sifa_registration_id');
            $table->string('nominee_name');
            $table->string('relation_with_employee');
            $table->string('cid_number');
            $table->float('percentage_of_share');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sifa_and_retirement_nominations');
    }
};
