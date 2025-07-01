<?php

/**
 * Define all the global constants that nedd to be used accress application.
 */

const GENERAL_ERR_MSG = "An error occurred while processing your request. It seems like some required information is missing or invalid. Please ensure all necessary fields are filled in and try again.";

const ADVANCE_TO_STAFF = 1;
const DSA_ADVANCE = 2;
const ELECTRICITY_IMPREST_ADVANCE = 3;
const GADGET_EMI = 4;
const IMPREST_ADVANCE = 5;
const SALARY_ADVANCE = 6;
const SIFA_LOAN = 7;
const SIFA_INTEREST_RATE = 15;
const CONVEYANCE_EXPENSE = 1;

const DOMESTIC_TRAVEL_TYPE = 1;

//constant extracted from approving_authoritioes tbl
const IMMEDIATE_HEAD = 6;
const DEPARTMENT_HEAD = 7;
const MANAGING_DIRECTOR = 8;
const ADMIN = 1;
const HR = 3;
const HR_MANAGER = 11;
const ATTENDANCE_MANAGER = 16;

//approval head constant from mas_approval_heads table
const LEAVE_APPVL_HEAD = 1;
const EXPENSE_APPVL_HEAD = 2;
const ADVANCE_APPVL_HEAD = 3;
const LEAVE_ENCASHMENT_APPVL_HEAD = 4;
const REQUISITION_APPVL_HEAD = 5;
const TRANSFER_CLAIM_APPVL_HEAD = 6;
const TRAVEL_AUTHORIZATION_APPVL_HEAD = 7;
const SIFA_REGISTRATION_APPVL_HEAD = 8;
const DSA_CLAIM_SETTLEMENT_APPVL_HEAD = 9;
const COMMISSION_APPVL_HEAD = 10;
const ASSET_TRANSFER_APPVL_HEAD = 11;
const ASSET_RETURN_APPVL_HEAD = 12;

const DSA_CLAIM_SETTLEMENT_EXPENSE_TYPE = 3;
const TRANSFER_CLAIM_EXPENSE_TYPE = 4;


// Approval Option from mas_approval_conditions
const HIERARCHICAL_APPVL_OPTION = 1;
const SINGLE_USER_APPVL_OPTION = 2;
const AUTO_APPVL_OPTION = 1;

//daily allowance
const DAILY_ALLOWANCE = 1800;
const TRAVEL_ALLOWANCE = 1200;

//leave type constant
const CASUAL_LEAVE = 1;
const EARNED_LEAVE = 2;
const MEDICAL_LEAVE = 3;
const MATERNITY_LEAVE = 4;
const PATERNITY_LEAVE = 5;
const EXTRA_ORDINARY_LEAVE = 6;
const STUDY_LEAVE = 7;
const BEREAVEMENT_LEAVE = 8;
const EARNED_LEAVE_CREDIT_AMOUNT = 2.5;
const CASUAL_LEAVE_CREDIT_AMOUNT = 10;


// SAP constants for UAT;
// const SAP_BASE_URL = "https://192.168.196.20";
// const SAP_PORT = 50000;
// const SAP_CONPANY_DB = "TICL_DB_TST";
// const SAP_USERNAME = "manager";
// const SAP_PASSWORD = "TipL@2025";
// const DSA_ACCOUNT_CODE = 55511;


// SAP constants for LIVE;
const SAP_BASE_URL = "https://192.168.196.20";
const SAP_PORT = 50000;
const SAP_CONPANY_DB = "TICL_DB_PRD";
const SAP_USERNAME = "manager";
const SAP_PASSWORD = "TipL@2025";
const DSA_ACCOUNT_CODE = 501152;

// Payslip statuses
const SIFA_APPROVED = 3;
const NEWLY_CREATED = 1;
const PREPARED = 2;
const VERIFIED = 3;
const APPROVED_POSTED = 4;
const UNPAID_SALARY_STAFF = 205150;
const ALLOWANCE = 1;
const DEDUCTION = 2;


//Encashment Tax GL code
const TAX_GL_CODE = 205116; //for live
// const TAX_GL_CODE = 23173;

//Asset constant
const FIXED_ASSET = 1;
const CONSUMEABLE_ASSET = 2;
const COMMISSION_TYPE = 1;

const EMPLOYEE_EMPLOYEEE = 1;
const SITE_SITE = 2;

const ASSET_RETURN_TYPE =1;

//Attendance Status
const PRESENT_STATUS = 1;
const ABSENT_STATUS = 2;
const WEEKLY_OFF_STATUS = 23;
const HALF_DAY_HOLIDAY_STATUS = 24;
const HALF_DAY_WEEKEND_STATUS = 25;
const HOLIDAY_STATUS = 3;
const CASUAL_LEAVE_STATUS = 8;
const FHCL_LEAVE_STATUS = 9;
const SHCL_LEAVE_STATUS = 10;
const MEDICAL_LEAVE_STATUS = 12;
const EARNED_LEAVE_STATUS = 11;
const MATERNITY_LEAVE_STATUS = 13;
const PATERNITY_LEAVE_STATUS = 14;
const EOL_LEAVE_STATUS = 15;
const STUDY_LEAVE_STATUS = 16;
const BEREAVEMENT_LEAVE_STATUS = 17;
const ON_TOUR_STATUS = 19;
const CREATED_STATUS = 27;
const INFORMED_LATE_STATUS = 26;

const SIFA_LOAN_PAY_HEAD = 24;
