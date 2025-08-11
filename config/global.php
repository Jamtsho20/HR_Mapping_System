<?php
return [

    'general_error_msg' => 'An error occurred while processing your request. It seems like some required information is missing or invalid. Please ensure all necessary fields are filled in and try again.',

    'no_data_found_msg' => 'No Data Found!',

    'default_password' => 'password',

    'null_value' => '-',

    'pagination' => 50,

    'attendance_buffer_mins' => 6,

    'radius_unit' => 'm',

    'holiday_types' => ['Government', 'Private'],

    'level' => ['level 1', 'level 2', 'level 3'],
    'level_with_all' => ['ALL', 'level 1', 'level 2', 'level 3'],

    'value' => [
        'Immediate Supervisor',
        'Section Head',
        'Department Head',
        'Management',
        'Human Resource',
        'Finance Head'
    ],

    'status' => [
        1 => 'Active',
        0 => 'Inactive'
    ],

    'regular_emp_type_id' => 2,
    'probational_emp_type_id' => 3,

    'leave_limits' => [
        1 => 'Include Public Holidays',
        2 => 'Can be clubbed with CL',
        3 => 'Include Weekends',
        4 => 'Can be half day',
        5 => 'Can be clubbed with EL'

    ],

    'application_status' => [
        -1 => 'Rejected',
        0 => 'Cancelled',
        1 => 'Approval Pending',
        2 => 'Approval Pending',
        3 => 'Approved',
        4 => 'Disbursed',
    ],

    'attendance_status' => [
        1 => 'Submission Pending',
        2 => 'Submitted',
        3 => 'Finalized'
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
        3 => 'Divorced',
    ],

    'leave_year' => [
        1 => 'Calendar Year',
        2 => 'Financial Year'
    ],

    'nationality' => ['Bhutanese', 'Indian', 'Canadian'],

    'title' => ['Mr.', 'Miss.', 'Mrs.', 'Dr.', 'Dasho'],

    'bank' => [
        'BoB' => 'Bank of Bhutan',
        'BNB' => 'Bhutan National Bank',
        'BDB' => 'Bhutan Development Bank',
        'Druk PNB' => 'Druk PNB',
        'T Bank' => 'T Bank',
        'DK Bank' => 'Digital Kidu'
    ],
    'sap_bank_code' => [
        'BoB' => 'BOBL',
        'BNB' => 'BNBL',
        'BDB' => 'BDBL',
        'Druk PNB' => 'DPNB-TH1',
        'T Bank' => 'TBANK-TH',
        // 'DK Bank' => 'Digital Kidu'
    ],

    'loan_type' => [
        'Consumer Loan',
        'Personal Loan',
        'Employee Loan',
        'Vehicle Loan',
        'Festival Loan',
        'PPF',
        'Housing Loan'
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
        // 4 => 'Shift'
    ],

    'salary_disbursement_mode' => [
        1 => 'Cash',
        2 => 'Saving Account'
    ],

    'travel_modes' => [
        1 => 'Bike',
        2 => 'Bus',
        3 => 'Car',
        4 => 'Flight',
        5 => 'Train'
    ],

    'travel_types' => [
        1 => 'Domestic'
    ],

    'no_of_emi' => [
        3 => '3 Months',
        6 => '6 Months',
        9 => '9 Months',
        12 => '12 Months',
    ],
    'no_of_emis' => [
        12 => '12 Months',
    ],

    'rate_limits' => [
        1 => 'Daily',
        2 => 'Monthly',
        3 => 'Yearly',
    ],

    'vehicle_types' => [
        1 => 'Light',
        2 => 'Medium',
        3 => 'Heavy',
        4 => 'Two Wheeler'
    ],

    'status_classes' => [
        -1 => 'badge bg-danger',
        0 => 'badge bg-warning',
        1 => 'badge bg-primary',
        2 => 'badge bg-primary',
        3 => 'badge bg-info',
    ],

    'applications' => [
        1 => [
            'name' => App\Models\LeaveApplication::class,
            'post_to_sap' => false,
            'email_subject' => 'Leave',
            'approver_mail_content' => 'has applied {no_of_days} day(s) of {type} from {from_date} to {to_date}.',
            'initiator_mail_content' => 'Your {no_of_days} day(s) of {type} from {from_date} to {to_date} has been'
        ],
        2 => [
            'name' => App\Models\ExpenseApplication::class,
            'post_to_sap' => true,
            'email_subject' => 'Expense',
            'approver_mail_content' => 'has applied {type} for your endorsement.',
            'initiator_mail_content' => 'Your expense has been'
        ],
        3 => [
            'name' => App\Models\AdvanceApplication::class,
            'post_to_sap' => true,
            'email_subject' => 'Advance',
            'approver_mail_content' => 'has applied {type} for your endorsement.',
            'initiator_mail_content' => 'Your Loan has been'
        ],
        4 => [
            'name' => App\Models\LeaveEncashmentApplication::class,
            'post_to_sap' => true,
            'email_subject' => 'Leave Encashment',
            'approver_mail_content' => 'has applied Leave Encashment for your endorsement.',
            'initiator_mail_content' => 'Your Leave Encashment has been'
        ],
        5 => [
            'name' => App\Models\RequisitionApplication::class,
            'post_to_sap' => true,
            'email_subject' => 'Requisition',
            'approver_mail_content' => 'has applied Requisition of {type} for your endorsement.',
            'initiator_mail_content' => 'Your Requisition has been'

        ],
        6 => [
            'name' => App\Models\TransferClaimApplication::class,
            'post_to_sap' => true,
            'email_subject' => 'Transfer Claim',
            'approver_mail_content' => 'has applied Transfer Claim of {type} for your endorsement.',
            'initiator_mail_content' => 'your Transfer Claim has been'
        ],
        7 => [
            'name' => App\Models\TravelAuthorizationApplication::class,
            'post_to_sap' => false,
            'email_subject' => 'Travel Authorization',
            'approver_mail_content' => 'has applied travel authorization of {type} for your endorsement.',
            'initiator_mail_content' => 'Your Travel Authorization has been'
        ],
        8 => [
            'name' => App\Models\SifaRegistration::class,
            'post_to_sap' => false,
            'email_subject' => 'Sifa Registration',
            'approver_mail_content' => 'has applied Sifa Registration for your endorsement.',
            'initiator_mail_content' => 'Your Sifa Registration has been'
        ],
        9 => [
            'name' => App\Models\DsaClaimApplication::class,
            'post_to_sap' => true,
            'email_subject' => 'DSA Claim',
            'approver_mail_content' => 'has applied DSA Claim of {type} for your endorsement.',
            'initiator_mail_content' => 'Your DSA Claim has been'
        ],
        10 => [
            'name' => App\Models\AssetCommissionApplication::class,
            'post_to_sap' => true,
            'email_subject' => 'Asset Commission',
            'approver_mail_content' => 'has applied asset commission for your endorsement.',
            'initiator_mail_content' => 'Your asset commission has been'
        ],
        11 => [
            'name' => App\Models\AssetTransferApplication::class,
            'post_to_sap' => false,
            'email_subject' => 'Asset Transfer',
            'approver_mail_content' => 'has applied asset transfer for your endorsement.',
            'initiator_mail_content' => 'Your asset transfer has been'
        ],
        12 => [
            'name' => App\Models\AssetReturnApplication::class,
            'post_to_sap' => true,
            'email_subject' => 'Asset Return',
            'approver_mail_content' => 'has applied asset commission for your endorsement.',
            'initiator_mail_content' => 'Your asset commission has been'
        ],
        13 => [
            'name' => App\Models\RetirementBenefit::class,
            'post_to_sap' => false,
            'email_subject' => 'Retirement Benefit Nomination',
            'approver_mail_content' => 'has applied retirement benefit for your endorsement.',
            'initiator_mail_content' => 'Your retirement benefit has been'
        ]

    ],

    'asset_condition_codes' => [
        1 => 'Beyond Economic Repair',
        2 => 'Absolete',
        3 => 'Working',
        4 => 'Damaged',
    ],

    'seasons' => [
        1 => 'Spring',
        2 => 'Summer',
        3 => 'Autumn',
        4 => 'Winter'
    ],

    'months' => [
        1 => 'JAN', 2 => 'FEB', 3 => 'MAR', 4 =>'APR',
        5 => 'MAY', 6 => 'JUN', 7 => 'JUL', 8 => 'AUG',
        9 => 'SEP', 10 => 'OCT', 11 => 'NOV', 12 => 'DEC',
    ],

    'years' => [
        2024, 2025, 2026,
    ],

];
