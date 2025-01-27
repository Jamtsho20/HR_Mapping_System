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
        Schema::create('mas_leave_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained('mas_leave_types');
            $table->string('name', 50)->comment('leave policy name');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('status')->default(0)->comment('0 => draft, 1 => enforced');
            $table->boolean('is_information_only')->default(1)->comment('1 => just for information, 0 => used in later part of the application');
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
        Schema::dropIfExists('mas_leave_policies');
    }
};
