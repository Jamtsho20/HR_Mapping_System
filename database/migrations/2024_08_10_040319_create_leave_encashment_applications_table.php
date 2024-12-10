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
        Schema::create('leave_encashment_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_employee_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('type_id')->constrained('leave_encashment_types')->cascadeOnDelete()->cascadeOnUpdate();
            // $table->float('total_leave_for_encashment')->comment('No. of days that the emp have that can be encashed');
            // $table->float('leave_eligible_for_encashment')->comment('No. of days that is eligible for encashment');
            $table->float('leave_applied_for_encashment')->comment('No. of days employee have applied for encashment');
            $table->decimal('encashment_amount', 16,2);
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
        Schema::dropIfExists('leave_encashment_applications');
    }
};
