<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
        CREATE TRIGGER before_employee_leaves_update
        BEFORE UPDATE ON employee_leaves
        FOREACH ROW
            BEGIN
            INSERT INTO employee_leaves_logs (
                employee_leave_id,
                opening_balance,
                current_entitlement,
                leaves_availed,
                closing_balance,
                created_at,
                updated_at,
                logged_at,
            ) VALUES (
                OLD.id,
                OLD.mas_leave_type_id,
                OLD.mas_employee_id,
                OLD.opening_balance,
                OLD.current_entitlement,
                OLD.leaves_availed,
                OLD.closing_balance,
                OLD.created_by,
                OLD.updated_by,
                OLD.created_at,
                OLD.updated_at,
                NOW());'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS before_employee_leaves_update');
    }
};
