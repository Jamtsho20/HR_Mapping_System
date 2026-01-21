<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    // Always drop first to avoid "already exists" error
    DB::unprepared('DROP TRIGGER IF EXISTS trigger_emp_leave_credit');
    DB::unprepared('DROP PROCEDURE IF EXISTS process_leave_credits');

    // Create the stored procedure
    DB::unprepared("
        CREATE PROCEDURE process_leave_credits(
            IN emp_id INT, 
            IN emp_gender INT, 
            IN created_by INT, 
            IN updated_by INT, 
            IN joining_date DATE
        )
        BEGIN
            DECLARE done INT DEFAULT 0;
            DECLARE leave_id INT;
            DECLARE leave_type_name VARCHAR(255);
            DECLARE duration DECIMAL(4, 1);
            DECLARE emp_type_id INT;
            DECLARE total_months_remaining INT;
            DECLARE leave_entitlement INT;
            DECLARE grade_step_id INT;

            DECLARE leave_cursor CURSOR FOR
                SELECT t1.id, t1.name
                FROM mas_leave_types t1
                JOIN mas_leave_policies t2 ON t1.id = t2.type_id
                WHERE t2.status = 1 AND t2.is_information_only = 0;

            DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

            SELECT mas_employment_type_id, mas_grade_step_id
            INTO emp_type_id, grade_step_id
            FROM mas_employee_jobs
            WHERE mas_employee_id = emp_id
            LIMIT 1;

            OPEN leave_cursor;

            leave_loop: LOOP
                FETCH leave_cursor INTO leave_id, leave_type_name;
                IF done THEN
                    LEAVE leave_loop;
                END IF;

                SELECT COALESCE(t3.duration, 0)
                INTO duration
                FROM mas_leave_policies t1
                LEFT JOIN leave_policy_plans t2 ON t1.id = t2.mas_leave_policy_id
                LEFT JOIN leave_policy_rules t3 ON t2.id = t3.leave_policy_plan_id
                WHERE t1.type_id = leave_id
                    AND t1.status = 1
                    AND t1.is_information_only = 0
                    AND t3.mas_grade_step_id = grade_step_id
                    AND (t3.mas_employment_type_id = 1 OR t3.mas_employment_type_id = emp_type_id)
                    AND (t2.gender = 3 OR t2.gender = emp_gender)
                    AND t3.status = 1
                LIMIT 1;

                IF (duration) THEN
                    IF (leave_id = 1) THEN
                        SET total_months_remaining = 12 - MONTH(joining_date) + 1;
                        SET leave_entitlement = ROUND((total_months_remaining / 12) * duration);
                    ELSEIF (leave_id = 2) THEN
                        SET leave_entitlement = 0;
                    ELSE
                        SET leave_entitlement = duration;
                    END IF;

                    IF NOT EXISTS (
                        SELECT 1 FROM employee_leaves
                        WHERE mas_employee_id = emp_id
                          AND mas_leave_type_id = leave_id
                    ) THEN
                        INSERT INTO employee_leaves (
                            mas_leave_type_id,
                            mas_employee_id,
                            opening_balance,
                            current_entitlement,
                            leaves_availed,
                            closing_balance,
                            created_by,
                            updated_by,
                            created_at,
                            updated_at
                        ) VALUES (
                            leave_id,
                            emp_id,
                            0,
                            leave_entitlement,
                            0,
                            leave_entitlement,
                            created_by,
                            updated_by,
                            NOW(),
                            NOW()
                        );
                    END IF;
                END IF;
            END LOOP;

            CLOSE leave_cursor;
        END;
    ");

    // Create trigger
    DB::unprepared("
        CREATE TRIGGER trigger_emp_leave_credit
        AFTER UPDATE ON mas_employees
        FOR EACH ROW
        BEGIN
            IF (CAST(OLD.status AS UNSIGNED) = 0 AND CAST(NEW.status AS UNSIGNED) = 1) THEN
                CALL process_leave_credits(
                    NEW.id,
                    NEW.gender,
                    NEW.created_by,
                    NEW.updated_by,
                    NEW.date_of_appointment
                );
            END IF;
        END;
    ");
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the trigger and procedure if they exist
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_emp_leave_credit');
        DB::unprepared('DROP PROCEDURE IF EXISTS process_leave_credits');
    }
};
