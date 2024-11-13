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
        Schema::create('travel_authorizations', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger("mode_of_travel")->comment("1 for Bike, 2 for Bus, 3 for Car, 4 for Flight, 5 for Train")->index()->nullable();
            $table->string('from_location');
            $table->string('to_location');
            $table->date('date');
            $table->date('from_date');
            $table->date('to_date');
            $table->decimal("estimated_travel_expenses", 10, 2);
            $table->decimal("advance_amount", 10, 2)->nullable();
            $table->text('purpose')->nullable();
            $table->foreignId('created_by')->index()->constrained('mas_employees');
            $table->foreignId('updated_by')->index()->nullable()->constrained('mas_employees');
            $table->tinyInteger('status')->default(1)->comment('-1 => Rejected, 0 => cancelled/withdrawn, 1 => New, 2 => Approved');
            $table->decimal("daily_allowance", 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_authorizations');
    }
};
