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
        Schema::table('mas_employee_jobs', function (Blueprint $table) {
            $table->foreignId('mas_employment_type_id')->index()->constrained();
            $table->foreignId('immediate_supervisor')->index()->constrained('mas_employees')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('job_location', 100);
            $table->unsignedInteger('basic_pay');
            $table->string('bank', 50);
            $table->string('account_number');
            $table->string('pf_number', 100);
            $table->string('tpn_number', 100);
            // $table->string('grade_scale', 50);
            // $table->string('ceiling', 50);
            // $table->string('grade_ladder', 50);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mas_employee_jobs', function (Blueprint $table) {
            $table->dropColumn('mas_employment_type_id');
            $table->dropColumn('immediate_supervisor');
            $table->dropColumn('job_location');
            $table->dropColumn('basic_pay');
            $table->dropColumn('bank');
            $table->dropColumn('account_number');
            $table->dropColumn('pf_number');
            $table->dropColumn('tpn_number');
        });
    }
};
