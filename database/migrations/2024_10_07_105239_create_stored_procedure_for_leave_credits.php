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
        // Create the stored procedure  while creating procedur always make sure to declare variable right after BEGIN statement
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
                DECLARE duration INT;
                DECLARE emp_type_id INT;
                DECLARE total_months_remaining INT;
                DECLARE leave_entitlement INT;
                DECLARE grade_step_id INT;
                 -- Declare the cursor to loop through all applicable leave types
                DECLARE leave_cursor CURSOR FOR
                    SELECT id, name
                    FROM mas_leave_types;

                -- Continue handler for cursor
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

                -- Get employment type and grade step of the newly inserted employee
                SELECT mas_employment_type_id, mas_grade_step_id
                INTO emp_type_id, grade_step_id
                FROM mas_employee_jobs
                WHERE mas_employee_id = emp_id
                LIMIT 1;

                -- Open the cursor
                OPEN leave_cursor;

                -- Start the loop
                leave_loop: LOOP
                    FETCH leave_cursor INTO leave_id, leave_type_name;
                    IF done THEN
                        LEAVE leave_loop;
                    END IF;

                    -- Get leave policy plan and leave policy rules
                    SELECT t3.duration
                        INTO duration
                    FROM mas_leave_policies t1
                        LEFT JOIN leave_policy_plans t2 ON t1.id = t2.mas_leave_policy_id
                        LEFT JOIN leave_policy_rules t3 ON t2.id = t3.leave_policy_plan_id
                    WHERE t1.mas_leave_type_id = leave_id
                        AND t1.status = 1
                        AND t1.is_information_only = 1
                        AND t3.mas_grade_step_id = grade_step_id
                        AND t3.mas_employment_type_id = emp_type_id
                        AND (t2.gender = 3 OR t2.gender = emp_gender)
                        AND t3.status = 1
                    LIMIT 1;

                    -- Conditional check based on leave_type_name
                    IF (leave_type_name = 'Casual Leave') THEN
                        -- Calculate the number of months remaining in the year from the joining date
                        SET total_months_remaining = 12 - MONTH(joining_date) + 1;
                        -- Proportionally calculate the leave entitlement
                        SET leave_entitlement = ROUND((total_months_remaining / 12) * duration);
                    ELSEIF (leave_type_name = 'Study Leave') THEN
                    -- Study Leave will be set to 0 because study leave will be eligible only after 2 years of service  
                        SET leave_entitlement = 0;  
                    ELSE
                        SET leave_entitlement = duration;
                    END IF;

                    -- Insert leave entitlement into employee_leaves table
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
                    )
                    VALUES (
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

                END LOOP leave_loop;

                -- Close the cursor
                CLOSE leave_cursor;
            END;
        ");

        // Create the trigger to call the stored procedure
        DB::unprepared("
            CREATE TRIGGER trigger_emp_leave_credit
            AFTER UPDATE ON mas_employees
            FOR EACH ROW
            BEGIN
                IF NEW.status = 1 THEN
                    CALL process_leave_credits(NEW.id, NEW.gender, NEW.created_by, NEW.updated_by, NEW.date_of_appointment);
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
