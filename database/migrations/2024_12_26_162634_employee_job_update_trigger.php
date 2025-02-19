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

					 DECLARE updater_id INT;
					 SELECT updated_by INTO updater_id FROM mas_employees WHERE id = NEW.mas_employee_id;

					 IF (OLD.mas_department_id IS NULL AND NEW.mas_department_id IS NOT NULL)
       OR (OLD.mas_department_id IS NOT NULL AND OLD.mas_department_id != NEW.mas_department_id)
       OR (OLD.mas_section_id IS NULL AND NEW.mas_section_id IS NOT NULL)
       OR (OLD.mas_section_id IS NOT NULL AND OLD.mas_section_id != NEW.mas_section_id)
       OR (OLD.mas_designation_id IS NULL AND NEW.mas_designation_id IS NOT NULL)
       OR (OLD.mas_designation_id IS NOT NULL AND OLD.mas_designation_id != NEW.mas_designation_id)
       OR (OLD.suffix IS NULL AND NEW.suffix IS NOT NULL)
       OR (OLD.suffix IS NOT NULL AND OLD.suffix != NEW.suffix)
       OR (OLD.mas_grade_id IS NULL AND NEW.mas_grade_id IS NOT NULL)
       OR (OLD.mas_grade_id IS NOT NULL AND OLD.mas_grade_id != NEW.mas_grade_id)
       OR (OLD.mas_grade_step_id IS NULL AND NEW.mas_grade_step_id IS NOT NULL)
       OR (OLD.mas_grade_step_id IS NOT NULL AND OLD.mas_grade_step_id != NEW.mas_grade_step_id)
       OR (OLD.has_probation IS NULL AND NEW.has_probation IS NOT NULL)
       OR (OLD.has_probation IS NOT NULL AND OLD.has_probation != NEW.has_probation)
       OR (OLD.mas_employment_type_id IS NULL AND NEW.mas_employment_type_id IS NOT NULL)
       OR (OLD.mas_employment_type_id IS NOT NULL AND OLD.mas_employment_type_id != NEW.mas_employment_type_id)
       OR (OLD.immediate_supervisor IS NULL AND NEW.immediate_supervisor IS NOT NULL)
       OR (OLD.immediate_supervisor IS NOT NULL AND OLD.immediate_supervisor != NEW.immediate_supervisor)
       OR (OLD.mas_office_id IS NULL AND NEW.mas_office_id IS NOT NULL)
       OR (OLD.mas_office_id IS NOT NULL AND OLD.mas_office_id != NEW.mas_office_id)
       OR (OLD.basic_pay IS NULL AND NEW.basic_pay IS NOT NULL)
       OR (OLD.basic_pay IS NOT NULL AND OLD.basic_pay != NEW.basic_pay)
		 THEN

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
                       updated_at,
                       updated_by
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
                       NOW(),
                       updater_id
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
