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
        Schema::create('travel_authorization_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('travel_authorization_id')->constrained('travel_authorizations')->onDelete('cascade');
            $table->string('from_location');
            $table->tinyInteger("mode_of_travel")->comment("1 for Bike, 2 for Bus, 3 for Car, 4 for Flight, 5 for Train")->index()->nullable();
            $table->string('to_location');
            $table->date('from_date');
            $table->date('to_date');
            $table->text('purpose')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_authorization_details');
    }
};
