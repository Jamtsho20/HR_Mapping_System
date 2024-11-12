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
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('mas_employee_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('mas_leave_type_id')->constrained()->restrictOnDelete()->cascadeOnUpdate();
            $table->date('from_date');
            $table->date('to_date');
            $table->unsignedSmallInteger('from_day')->comment('1 => Full Day, 2 => First Half, 3 => Second Half, 4 => Shift');
            $table->unsignedSmallInteger('to_day')->comment('1 => Full Day, 2 => First Half, 3 => Second Half, 4 => Shift');
            $table->float('no_of_days');
            $table->text('remarks')->nullable();
            $table->string('attachment')->nullable();
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
        Schema::dropIfExists('leave_applications');
    }
};
