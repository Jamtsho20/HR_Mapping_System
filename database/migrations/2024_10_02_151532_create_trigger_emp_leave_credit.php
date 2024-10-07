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
        // Creating the trigger using raw SQL
        DB::unprepared("
            CREATE TRIGGER trigger_emp_leave_credit
            AFTER INSERT ON mas_employees
            FOR EACH ROW
            BEGIN
                IF NEW.status = 1 THEN
                    -- Declare necessary variables
                    DECLARE leave_id INT;
                    DECLARE leave_type_name INT;
                    DECLARE duration INT;
                    DECLARE emp_type_id INT;
                    DECLARE total_months_remaining INT;
                    DECLARE leave_entitlement INT;
                    DECLARE grade_step_id INT;
                    DECLARE gender INT;

                    -- Get employment type of the newly inserted employee
                    SELECT mas_employment_type_id, mas_grade_step_id INTO emp_type_id, grade_step_id FROM mas_employee_jobs WHERE mas_employee_id = NEW.id LIMIT 1;

                    -- Declare a cursor to loop through all applicable leave types
                    DECLARE leave_cursor CURSOR FOR 
                        SELECT id, name 
                        FROM mas_leave_types;

                    -- Declare handlers for the cursor
                    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

                    -- Open the cursor
                    OPEN leave_cursor;

                    leave_loop: LOOP
                        -- Fetch leave type details
                        FETCH leave_cursor INTO leave_id, leave_type_name;
                        IF done THEN
                            LEAVE leave_loop;
                        END IF;

                        -- Get leave policy plan and leave policy rules
                        SELECT t2.gender, t3.duration FROM mas_leave_policies t1 
                            INTO gender, duration
                            LEFT JOIN leave_policy_plans t2 ON t1.id = t2.mas_leave_policy_id 
                            LEFT JOIN leave_policy_rules t3 ON t2.id = t3.leave_policy_plan_id
                        WHERE t1.mas_leave_type_id = leave_id
                            AND t1.status = 1
                            AND t1.is_information_only = 1
                            AND t3.mas_grade_step_id = grade_step_id
                            AND t3.mas_employment_type_id = emp_type_id
                            AND (t2.gender = 3 OR t2.gender = NEW.gender) -- Check for gender match or gender = 3 (all genders)
                            LIMIT 1;
                    

                        -- Conditional check based on leave_type_id or leave_type_name
                        IF (leave_type_name = 'Casual Leave') THEN

                            -- Calculate the number of months remaining in the year from the joining date
                            SET total_months_remaining = 12 - MONTH(NEW.joining_date) + 1;

                            -- Proportionally calculate the leave entitlement (assuming duration annually)
                            SET leave_entitlement = ROUND((total_months_remaining / 12) * duration);

                            -- Insert leave entitlement into employee_leaves table
                            INSERT INTO employee_leaves (mas_leave_type_id, mas_employee_id, opening_balance, current_entitlement, leaves_availed, closing_balance, created_by, updated_by, created_at, updated_at)
                            VALUES (leave_id, NEW.id, 0, leave_entitlement, 0, leave_entitlement, NEW.created_by, NEW.updated_by, NOW(), NOW());

                        ELSEIF (leave_type_name = 'Study Leave') THEN --study leave will be eligible only after 2 years of service in the company so it will be 0 by default

                            -- Insert leave entitlement into employee_leaves table
                            INSERT INTO employee_leaves (mas_leave_type_id, mas_employee_id, opening_balance, current_entitlement, leaves_availed, closing_balance, created_by, updated_by, created_at, updated_at)
                            VALUES (leave_id, NEW.id, 0, 0, 0, 0, NEW.created_by, NEW.updated_by, NOW(), NOW());

                        ELSE

                            -- Insert leave entitlement into employee_leaves table
                            INSERT INTO employee_leaves (mas_leave_type_id, mas_employee_id, opening_balance, current_entitlement, leaves_availed, closing_balance, created_by, updated_by, created_at, updated_at)
                            VALUES (leave_id, NEW.id, 0, duration, 0, duration, NEW.created_by, NEW.updated_by, NOW(), NOW());

                        END IF;

                    END LOOP leave_loop;

                    -- Close the cursor after the loop
                    CLOSE leave_cursor;
                END IF;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Dropping the trigger if exists
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_emp_leave_credit');
    }
};
