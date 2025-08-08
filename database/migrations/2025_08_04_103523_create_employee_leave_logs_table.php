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
        Schema::create('employee_leave_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_leave_id')->constrained('employee_leaves')->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('opening_balance', 4, 1)->default(0);
            $table->decimal('current_entitlement', 4, 1)->default(0);
            $table->decimal('leaves_availed', 4, 1)->default(0);
            $table->decimal('closing_balance', 4, 1)->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('logged_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_leave_logs');
    }
};
