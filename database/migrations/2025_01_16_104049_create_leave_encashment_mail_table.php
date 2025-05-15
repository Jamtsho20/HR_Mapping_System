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
            $table->unsignedBigInteger('mas_employee_id')->index(); // Employee ID
            $table->boolean('email_sent')->default(false); // Email notification status
            $table->timestamp('sent_at')->nullable(); // When the email was sent
            $table->timestamps(); // Created at and updated at

            // Add foreign key if applicable
            $table->foreign('mas_employee_id')
                ->references('id')->on('mas_employees')
                ->onDelete('cascade');
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
