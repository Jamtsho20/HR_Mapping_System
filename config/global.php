<?php
return [
    'null_value' => 'N/A',

    'holiday_types' => ['Government', 'Private'],

    'level' => ['level 1', 'level 2','level 3'],

    'value' => [
        'Immediate Supervisor','Section Head','Department Head','Management','Human Resource','Finance Head'
    ],
    
    'status' => [
        1 =>'active', 0=>'inactive'
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

    'title' => ['Mr.', 'Mrs.', 'Dr.', 'Dasho'],

    'bank' => [
        'BoB' => 'Bank of Bhutan',
        'BNB' => 'Bhutan National Bank',
        'BDB' => 'Bhutan Development Bank',
        'Druk PNB' => 'Druk PNB',
        'T Bank' => 'T Bank',
        'DK Bank' => 'Digital Kidu'
    ],

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
    ]
];
