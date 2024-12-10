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
        Schema::create('expense_applications', function (Blueprint $table) {
            $table->id();
            $table->string('expense_no')->index();
            // $table->foreignId('mas_employee_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->date('date');
            $table->foreignId('type_id')->constrained()->restrictOnDelete()->cascadeOnUpdate();
            $table->string('travel_type')->nullable();
            $table->string('travel_mode')->nullable();
            $table->date('travel_from_date')->nullable();
            $table->date('travel_to_date')->nullable();
            $table->string('travel_from')->nullable();
            $table->string('travel_to')->nullable();
            $table->integer('travel_distance')->nullable();
            $table->integer('expense_amount')->nullable();
            $table->string('description')->nullable();
            $table->string('file')->nullable();
            // $table->string('status')->nullable();
            $table->tinyInteger('status')->default(1)->comment('-1 => Rejected, 0 => cancelled/withdrawn, 1 => New, 2 => Approved');
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
        Schema::dropIfExists('expense_apply');
    }
};
