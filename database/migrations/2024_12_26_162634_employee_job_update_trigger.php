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
        DB::unprepared('
            CREATE TRIGGER before_mas_employee_jobs_update
            BEFORE UPDATE ON mas_employee_jobs
            FOR EACH ROW
            BEGIN
                IF OLD.mas_department_id != NEW.mas_department_id
                   OR OLD.mas_section_id != NEW.mas_section_id
                   OR OLD.mas_designation_id != NEW.mas_designation_id
                   OR OLD.suffix != NEW.suffix
                   OR OLD.mas_grade_id != NEW.mas_grade_id
                   OR OLD.mas_grade_step_id != NEW.mas_grade_step_id
                   OR OLD.has_probation != NEW.has_probation
                   OR OLD.mas_employment_type_id != NEW.mas_employment_type_id
                   OR OLD.immediate_supervisor != NEW.immediate_supervisor
                   OR OLD.mas_office_id != NEW.mas_office_id
                   OR OLD.basic_pay != NEW.basic_pay THEN

                   INSERT INTO employee_jobs_logs (
                       mas_employee_id,
                       mas_department_id,
                       mas_section_id,
                       mas_designation_id,
                       suffix,
                       mas_grade_id,
                       mas_grade_step_id,
                       has_probation,
                       mas_employment_type_id,
                       immediate_supervisor,
                       mas_office_id,
                       basic_pay,
                       created_at,
                       updated_at
                   ) VALUES (
                       OLD.mas_employee_id,
                       OLD.mas_department_id,
                       OLD.mas_section_id,
                       OLD.mas_designation_id,
                       OLD.suffix,
                       OLD.mas_grade_id,
                       OLD.mas_grade_step_id,
                       OLD.has_probation,
                       OLD.mas_employment_type_id,
                       OLD.immediate_supervisor,
                       OLD.mas_office_id,
                       OLD.basic_pay,
                       NOW(),
                       NOW()
                   );
                END IF;
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS before_mas_employee_jobs_update');
    }
};
