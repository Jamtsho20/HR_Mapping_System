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
const IMMEDIATE_HEAD = 1;
const DEPARTMENT_HEAD = 2;

//approval head constant from mas_approval_heads table
const LEAVE_APPVL_HEAD = 1;
const EXPENSE_APPVL_HEAD = 2;
const ADVANCE_APPVL_HEAD = 3;
const LEAVE_ENCASHMENT_APPVL_HEAD = 4;
const REQUISITION_APPVL_HEAD = 5;

// Approval Option from mas_approval_conditions
const HIERARCHICAL_APPVL_OPTION = 1;
const SINGLE_USER_APPVL_OPTION = 2;
const AUTO_APPVL_OPTION = 1;

//daily allowance
const DAILY_ALLOWANCE = 1800;