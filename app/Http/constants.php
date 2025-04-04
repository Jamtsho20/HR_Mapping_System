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
// const SAP_BASE_URL = "https://192.168.196.23";
// const SAP_PORT = 50000;
// const SAP_CONPANY_DB = "TIPL_DB_TEST";
// const SAP_USERNAME = "manager";
// const SAP_PASSWORD = "Sap@2024";

// SAP constants for LIVE;
const SAP_BASE_URL = "https://192.168.196.20";
const SAP_PORT = 50000;
const SAP_CONPANY_DB = "TICL_DB_PRD";
const SAP_USERNAME = "manager";
const SAP_PASSWORD = "Tipl@2025";

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
