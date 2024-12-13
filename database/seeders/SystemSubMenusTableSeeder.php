<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SystemSubMenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('system_sub_menus')->insert([
            ['system_menu_id' => 1, 'name' => 'Modules', 'route' => 'system-setting/modules', 'display_order' => 1],
            ['system_menu_id' => 1, 'name' => 'Roles', 'route' => 'system-setting/roles', 'display_order' => 2],
            ['system_menu_id' => 1, 'name' => 'Users', 'route' => 'system-setting/users', 'display_order' => 3],
            ['system_menu_id' => 1, 'name' => 'Hierarchy', 'route' => 'system-setting/hierarchies', 'display_order' => 4],
            ['system_menu_id' => 1, 'name' => 'Delegation', 'route' => 'system-setting/delegations', 'display_order' => 5],
            ['system_menu_id' => 1, 'name' => 'Notification', 'route' => 'system-setting/notifications', 'display_order' => 6],
            ['system_menu_id' => 1, 'name' => 'Approval Head', 'route' => 'system-setting/approval-head', 'display_order' => 7],
            ['system_menu_id' => 1, 'name' => 'Approval Rules', 'route' => 'system-setting/approval-rules', 'display_order' => 8],
            ['system_menu_id' => 1, 'name' => 'Approving Authority', 'route' => 'system-setting/approving-authorities', 'display_order' => 9],
            ['system_menu_id' => 1, 'name' => 'Approval Condition Field', 'route' => 'system-setting/condition-fields', 'display_order' => 10],
            ['system_menu_id' => 1, 'name' => 'Budget Code', 'route' => 'system-setting/bugdet-code', 'display_order' => 11],

            //sub menu for master
            ['system_menu_id' => 2, 'name' => 'Employment Types', 'route' => 'master/employment-types', 'display_order' => 1],
            ['system_menu_id' => 2, 'name' => 'Leave Types', 'route' => 'master/leave-types', 'display_order' => 2],
            ['system_menu_id' => 2, 'name' => 'Department', 'route' => 'master/departments', 'display_order' => 3],
            ['system_menu_id' => 2, 'name' => 'Section', 'route' => 'master/section', 'display_order' => 4],
            ['system_menu_id' => 2, 'name' => 'Designation', 'route' => 'master/designations', 'display_order' => 5],
            ['system_menu_id' => 2, 'name' => 'Dzongkhag', 'route' => 'master/dzongkhags', 'display_order' => 6],
            ['system_menu_id' => 2, 'name' => 'Gewog', 'route' => 'master/gewogs', 'display_order' => 7],
            ['system_menu_id' => 2, 'name' => 'Village', 'route' => 'master/villages', 'display_order' => 8],
            ['system_menu_id' => 2, 'name' => 'Qualification', 'route' => 'master/qualifications', 'display_order' => 9],
            ['system_menu_id' => 2, 'name' => 'Grade & Steps', 'route' => 'master/grade-steps', 'display_order' => 10],
            ['system_menu_id' => 2, 'name' => 'Resignation Types', 'route' => 'master/resignation-types', 'display_order' => 11],
            ['system_menu_id' => 2, 'name' => 'Nationality', 'route' => 'master/nationalities', 'display_order' => 12],
            ['system_menu_id' => 2, 'name' => 'Regions', 'route' => 'master/regions', 'display_order' => 13],
            ['system_menu_id' => 2, 'name' => 'Expense Types', 'route' => 'master/expense-types', 'display_order' => 14],
            ['system_menu_id' => 2, 'name' => 'Offices', 'route' => 'master/offices', 'display_order' => 15],
            ['system_menu_id' => 2, 'name' => 'vehicles', 'route' => 'master/vehicles', 'display_order' => 16],

            //sub menu for work structure
            ['system_menu_id' => 3, 'name' => 'Holiday List', 'route' => 'work-structure/holiday-lists', 'display_order' => 1],
            ['system_menu_id' => 3, 'name' => 'Business Unit', 'route' => 'work-structure/business-unit', 'display_order' => 2],
            ['system_menu_id' => 3, 'name' => 'Geography', 'route' => 'work-structure/geography', 'display_order' => 3],

            //sub menu for Pay master
            ['system_menu_id' => 4, 'name' => 'Account Heads', 'route' => 'paymaster/account-heads', 'display_order' => 1],
            ['system_menu_id' => 4, 'name' => 'Pay Groups', 'route' => 'paymaster/pay-groups', 'display_order' => 2],
            ['system_menu_id' => 4, 'name' => 'Pay Slabs', 'route' => 'paymaster/pay-slabs', 'display_order' => 3],
            ['system_menu_id' => 4, 'name' => 'Pay Heads', 'route' => 'paymaster/pay-heads', 'display_order' => 4],
            ['system_menu_id' => 4, 'name' => 'Employee Group', 'route' => 'employee-group/employee-create', 'display_order' => 5],


            //sub menu for expense
            ['system_menu_id' => 5, 'name' => 'Apply', 'route' => 'expense/apply-expense', 'display_order' => 1],
            ['system_menu_id' => 5, 'name' => 'Policy', 'route' => 'expense/expense-policy', 'display_order' => 2],
            ['system_menu_id' => 5, 'name' => 'Approval', 'route' => 'expense/approval', 'display_order' => 3],
            ['system_menu_id' => 5, 'name' => 'DSA Claim/Settlement', 'route' => 'expense/dsa-claim-settlement', 'display_order' => 4],
            ['system_menu_id' => 5, 'name' => 'DSA Approval', 'route' => 'expense/dsa-approval', 'display_order' => 5],
            ['system_menu_id' => 5, 'name' => 'Transfer Claim', 'route' => 'expense/transfer-claim', 'display_order' => 6],
            ['system_menu_id' => 5, 'name' => 'Transfer Claim Approval', 'route' => 'expense/transfer-claim-approval', 'display_order' => 7],
            ['system_menu_id' => 5, 'name' => 'Expense Fuel /Fuel Claim', 'route' => 'expense/expense-fuel', 'display_order' => 8],
            ['system_menu_id' => 5, 'name' => 'Fuel Expense Approval', 'route' => 'expense/fuel-approval', 'display_order' => 9],

            //sub menu for leaves
            ['system_menu_id' => 6, 'name' => 'Leave Policy', 'route' => 'leave/leave-policy', 'display_order' => 1],
            ['system_menu_id' => 6, 'name' => 'Apply', 'route' => 'leave/leave-apply', 'display_order' => 2],
            ['system_menu_id' => 6, 'name' => 'Approval', 'route' => 'leave/approval', 'display_order' => 2],
            ['system_menu_id' => 6, 'name' => 'Cancellation', 'route' => 'leave/cancellation', 'display_order' => 3],
            ['system_menu_id' => 6, 'name' => 'History', 'route' => 'leave/leave-history', 'display_order' => 4],
            ['system_menu_id' => 6, 'name' => 'Encashment Approval', 'route' => 'leave/encashment-approval', 'display_order' => 5],

            //submenu for advance/loans
            ['system_menu_id' => 7, 'name' => 'Types', 'route' => 'advance-loan/types', 'display_order' => 1],
            ['system_menu_id' => 7, 'name' => 'Apply', 'route' => 'advance-loan/apply', 'display_order' => 2],
            ['system_menu_id' => 7, 'name' => 'Approval', 'route' => 'advance-loan/advance-loan-approval', 'display_order' => 3],

            //submenu for advance/loans
            ['system_menu_id' => 8, 'name' => 'Attendance Entry', 'route' => 'attendance/attendance-entry', 'display_order' => 1],
            ['system_menu_id' => 8, 'name' => 'Attendance Register', 'route' => 'attendance/attendance-register', 'display_order' => 2],
            ['system_menu_id' => 8, 'name' => 'Attendance Summary', 'route' => 'attendance/attendance-summary', 'display_order' => 3],

            //submenu for delegation approval
            ['system_menu_id' => 9, 'name' => 'Leave Delegation Approval', 'route' => 'delegation-approval/leave-delegation-approval', 'display_order' => 1],
            ['system_menu_id' => 9, 'name' => 'Exp. Delegation Approval', 'route' => 'delegation-approval/exp-delegation-approval', 'display_order' => 2],
            ['system_menu_id' => 9, 'name' => 'Fuel Delegation Approval', 'route' => 'delegation-approval/fuel-delegation-approval', 'display_order' => 3],
            ['system_menu_id' => 9, 'name' => 'DSA Delegation Approval', 'route' => 'delegation-approval/dsa-delegation-approval', 'display_order' => 4],
            ['system_menu_id' => 9, 'name' => 'Transfer Delegation Approval', 'route' => 'delegation-approval/transfer-delegation-approval', 'display_order' => 5],
            ['system_menu_id' => 9, 'name' => 'Adv. Loan Delegation', 'route' => 'delegation-approval/adv-loan-delegation', 'display_order' => 6],
            ['system_menu_id' => 9, 'name' => 'Approval', 'route' => 'delegation-approval/approval', 'display_order' => 7],

            //submenu for employee
            ['system_menu_id' => 10, 'name' => 'Employee', 'route' => 'employee/employee-lists', 'display_order' => 1],

            //submenu for sifa
            ['system_menu_id' => 11, 'name' => 'SIFA', 'route' => 'sifa/sifa-registration', 'display_order' => 1],
            ['system_menu_id' => 11, 'name' => 'SIFA', 'route' => 'sifa/sifa-approval', 'display_order' => 2],
            ['system_menu_id' => 11, 'name' => 'SIFA', 'route' => 'sifa/sifa-registered-user', 'display_order' => 3],

            //submenu for asset
            ['system_menu_id' => 13, 'name' => 'Store Master', 'route' => 'asset/mas-store', 'display_order' => 1],
            ['system_menu_id' => 13, 'name' => 'Requisiton-Apply', 'route' => 'asset/requisition-apply', 'display_order' => 2],
            ['system_menu_id' => 13, 'name' => 'Requisition-History', 'route' => 'asset/requisition-history', 'display_order' => 3],
            ['system_menu_id' => 13, 'name' => 'Requisition-Approval', 'route' => 'asset/requisition-approval', 'display_order' => 4],
            ['system_menu_id' => 13, 'name' => 'Goods-Issue', 'route' => 'asset/goods-issue', 'display_order' => 5],
            ['system_menu_id' => 13, 'name' => 'Goods-Issue-History', 'route' => 'asset/goods-issue-history', 'display_order' => 6],
            ['system_menu_id' => 13, 'name' => 'Goods-Receipt', 'route' => 'asset/goods-receipt', 'display_order' => 7],
            ['system_menu_id' => 13, 'name' => 'Goods-Receipt-History', 'route' => 'asset/goods-receipt-history', 'display_order' => 9],
            ['system_menu_id' => 13, 'name' => 'Commission', 'route' => 'asset/commission', 'display_order' => 10],
            ['system_menu_id' => 13, 'name' => 'Commission-History', 'route' => 'asset/commission-history', 'display_order' => 11],
            ['system_menu_id' => 13, 'name' => 'Commission-Approval', 'route' => 'asset/commission-approval', 'display_order' => 12],
            ['system_menu_id' => 13, 'name' => 'Asset-Transfer', 'route' => 'asset/asset-transfer', 'display_order' => 13],
            ['system_menu_id' => 13, 'name' => 'Transfer-History', 'route' => 'asset/transfer-history', 'display_order' => 14],
            ['system_menu_id' => 13, 'name' => 'Transfer-Approval', 'route' => 'asset/transfer-approval', 'display_order' => 15],
            ['system_menu_id' => 13, 'name' => 'Fixed-Asset-return', 'route' => 'asset/fixed-asset-return', 'display_order' => 16],
            ['system_menu_id' => 13, 'name' => 'Fixed-Asset-return-History', 'route' => 'asset/fixed-asset-return-history', 'display_order' => 17],
            ['system_menu_id' => 13, 'name' => 'Fixed-Asset-return-Approval', 'route' => 'asset/fixed-asset-return-approval', 'display_order' => 18],

            //submenu for payroll
            ['system_menu_id' => 14, 'name' => 'Annual Increment', 'route' => 'payroll/annual-increment', 'display_order' => 1],
            ['system_menu_id' => 14, 'name' => 'Other Pay Changes', 'route' => 'payroll/other-pay-changes', 'display_order' => 2],
            ['system_menu_id' => 14, 'name' => 'Loan / Device EMI', 'route' => 'payroll/loan-emi-deductions', 'display_order' => 3],
            ['system_menu_id' => 14, 'name' => 'Payslip', 'route' => 'payroll/pay-slips', 'display_order' => 4],

            // LTC
            ['system_menu_id' => 15, 'name' => 'LTC', 'route' => 'ltc/ltc', 'display_order' => 1],
            //submenu for Report
            ['system_menu_id' => 16, 'name' => 'LTC', 'route' => 'report/ltc-report', 'display_order' => 1],
            ['system_menu_id' => 16, 'name' => 'Leave Availed Report', 'route' => 'report/leave-availed-report', 'display_order' => 1],
            ['system_menu_id' => 16, 'name' => 'Leave Balance Report', 'route' => 'report/leave-balance-report', 'display_order' => 2],
            ['system_menu_id' => 16, 'name' => 'Leave Encashment Report', 'route' => 'report/leave-encashment-report', 'display_order' => 3],
            ['system_menu_id' => 16, 'name' => 'Vehicle Fuel Report', 'route' => 'report/vehicle-fuel-report', 'display_order' => 4],
            ['system_menu_id' => 16, 'name' => 'Advance Loan Report', 'route' => 'report/advance-loan-report', 'display_order' => 5],
            ['system_menu_id' => 16, 'name' => 'Expense Report', 'route' => 'report/expense-and-advance-report', 'display_order' => 6],
            ['system_menu_id' => 16, 'name' => 'Salary Saving Scheme', 'route' => 'report/salary-saving-scheme', 'display_order' => 7],
            ['system_menu_id' => 16, 'name' => 'SIFA Contribution', 'route' => 'report/sifa-contribution', 'display_order' => 8],
            ['system_menu_id' => 16, 'name' => 'Salary Report', 'route' => 'report/salary-report', 'display_order' => 9],
            ['system_menu_id' => 16, 'name' => 'GIS Report', 'route' => 'report/gis-report', 'display_order' => 10],
            ['system_menu_id' => 16, 'name' => 'PF Report', 'route' => 'report/pf-report', 'display_order' => 11],
            ['system_menu_id' => 16, 'name' => 'Loan Report', 'route' => 'report/loan-report', 'display_order' => 12],
            ['system_menu_id' => 16, 'name' => 'Cheque Payment Report', 'route' => 'report/cheque-report', 'display_order' => 13],
            ['system_menu_id' => 16, 'name' => 'Cash Payment Report', 'route' => 'report/cash-report', 'display_order' => 14],
            ['system_menu_id' => 16, 'name' => 'Employee Report', 'route' => 'report/employee-report', 'display_order' => 15],
            ['system_menu_id' => 16, 'name' => 'Transfer Claim Report', 'route' => 'report/transfer-claim-report', 'display_order' => 16],
            ['system_menu_id' => 16, 'name' => 'DSA Settlement Report', 'route' => 'report/dsa-settlement-report', 'display_order' => 17],
            ['system_menu_id' => 16, 'name' => 'Samsung Deduction Report', 'route' => 'report/samsung-deduction-report', 'display_order' => 18],
            ['system_menu_id' => 16, 'name' => 'Pay Comparison Report', 'route' => 'report/pay-comparision-report', 'display_order' => 19],
            ['system_menu_id' => 16, 'name' => 'Tax Schedule Report', 'route' => 'report/tax-schedule-report', 'display_order' => 20],


            //submenu for Travel Authorization
            ['system_menu_id' => 17, 'name' => 'Apply', 'route' => 'travel-authorization/apply-travel-authorization', 'display_order' => 1],
            ['system_menu_id' => 17, 'name' => 'Approval', 'route' => 'travel-authorization/travel-authorization-approval', 'display_order' => 2],

            //submenu for Approval            ['system_menu_id' => 17, 'name' => 'Apply', 'route' => 'travel-authorization/apply-travel-authorization', 'display_order' => 1],
            ['system_menu_id' => 18, 'name' => 'Applications', 'route' => 'approval/applications', 'display_order' => 1],
        ]);
    }
}
