<?php
return [

    'default_password' => 'password',

    'null_value' => '-',

    'holiday_types' => ['Government', 'Private'],

    'level' => ['level 1', 'level 2','level 3'],
    'level_with_all' => ['ALL','level 1', 'level 2', 'level 3'],

    'value' => [
        'Immediate Supervisor','Section Head','Department Head','Management','Human Resource','Finance Head'
    ],

    'status' => [
        1 =>'active', 0=>'inactive'
    ],

    'regular_emp_type_id' => 2,
    'probational_emp_type_id' => 3,

    'leave_limits' => [
        1 => 'Include Public Holidays',
        2 => 'Can be clubbed with CL',
        3 => 'Include Weekends',
        4=> 'Can be half day',
        5=> 'Can be clubbed with EL'

    ],

    'application_status' => [
        -1 => 'Rejected',
        0 => 'Cancelled',
        1 => 'New',
        2 => 'Approved'
    ],

    'gender' => [
        1 => 'Male',
        2 => 'Female',
        3 => 'Other'
    ],

    'gender_with_all' => [
        1 => 'Male',
        2 => 'Female',
        3 => 'All'
    ],

    'marital_status' => [
        1 => 'Single',
        2 => 'Married',
        3 => 'Divorced'
    ],

    'leave_year' => [
        1 => 'Calendar Year',
        2 => 'Financial Year'
    ],

    'nationality' => ['Bhutanese', 'Indian', 'Canadian'],

    'title' => ['Mr.','Miss', 'Mrs.', 'Dr.', 'Dasho'],

    'bank' => [
        'BoB' => 'Bank of Bhutan',
        'BNB' => 'Bhutan National Bank',
        'BDB' => 'Bhutan Development Bank',
        'Druk PNB' => 'Druk PNB',
        'T Bank' => 'T Bank',
        'DK Bank' => 'Digital Kidu'
    ],

    'calculation__method' => [
        1 => 'Actual Amount',
        2 => 'Division',
        3 => 'Slab Wise',
        4 => 'Group Wise',
        5 => 'Percentage',
        6 => 'By Formula',
        7 => 'Employee Wise',
    ],
// 1 for Basic Pay, 2 for Gross Pay, 3 for Net Pay, 4 for PIT Net Pay, 5 for By Formula, 6 for Pay Scale Base Pay
    'calculated_on' => [ // for payheads
        1 => 'Basic Pay',
        2 => 'Gross Pay',
        3 => 'Net Pay',
        4 => 'PIT Net Pay',
        5 => 'By Formula',
        6 => 'Pay Scale Base Pay'
    ],
    // 1 for Actual Amount, 2 for Division, 3 for Slab Wise, 4 for Group Wise, 5 for Percentage, 6 for By Formula, 7 for Employee Wise
    'calculation_methods_for_payheads' => [ //payheads
        1 => 'Actual Amount',
        2 =>  'Division',
        3 =>  'Slab Wise',
        4 => 'Group Wise',
        5 => 'Percentage',
        6 => 'By Formula',
        7 => 'Employee Wise'
    ],

    'calculation_method' => [ //for pay groups
        0 => 'N/A',
        1 => 'Actual Method',
        2 => 'Division',
        3 => 'Percentage'
    ],

    'leave_days' => [ //leave days(from_day, to_day)
        1 => 'Full Day',
        2 => 'First Half',
        3 => 'Second Half',
        4 => 'Shift'
    ],

    'salary_disbursement_mode' => [
        1 => 'Cash',
        2 => 'Saving Account'    
    ],

    'general_error_msg' => 'An error occurred while processing your request. It seems like some required information is missing or invalid. Please ensure all necessary fields are filled in and try again.',

    'no_of_emi' => [
        1 => 3,
        2 => 6,
        3 => 9,
        4 => 12
    ],
];
