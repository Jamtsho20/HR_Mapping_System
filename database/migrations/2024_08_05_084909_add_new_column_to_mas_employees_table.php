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
        Schema::table('mas_employees', function (Blueprint $table) {
            $table->string('first_name', 50);
            $table->string('middle_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('title', 50);
            $table->string('cid_no', 50);
            $table->string('employee_id', 30)->unique();
            $table->string('gender', 30);
            $table->date('dob')->default(now());
            $table->string('birth_place', 100)->nullable();
            $table->string('birth_country', 100)->nullable();
            $table->string('marital_status', 50);
            $table->integer('contact_number');
            $table->string('nationality', 50);
            $table->date('date_of_appointment')->default(now());
            $table->string('cid_copy');
            $table->tinyInteger('no_probation')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('registered_email_sent')->default(0);
            $table->tinyInteger('encashment_email_sent')->default(0);
            $table->date('regularized_on')->nullable();
            $table->string('appointment_order')->nullable();
            $table->string('regular_appointment_order')->nullable();
            $table->tinyInteger('is_regularized')->default(0);
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mas_employees', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('middle_name');
            $table->dropColumn('last_name');
            $table->dropColumn('title');
            $table->dropColumn('cid_no');
            $table->dropColumn('dob');
            $table->dropColumn('birth_place');
            $table->dropColumn('birth_country');
            $table->dropColumn('marital_status');
            $table->dropColumn('contact_number');
            $table->dropColumn('nationality');
            $table->dropColumn('date_of_appointment');
            $table->dropColumn('cid_copy');
            $table->dropColumn('status');
            $table->dropColumn('registered_email_sent');
            $table->dropColumn('encashment_email_sent');
            $table->dropColumn('regularized_on');
        });
    }
};
