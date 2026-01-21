<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('leave_encashment_mail_table', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mas_employee_id')
                ->constrained('mas_employees')
                ->cascadeOnDelete();

            $table->boolean('email_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_encashment_mail_table');
    }
};
