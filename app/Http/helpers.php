<?php

use App\Models\ApplicationAuditLog;
use App\Models\ApplicationHistory;
use App\Models\LeaveApplication;
use App\Models\MasAttendanceFeature;
use App\Models\MasConditionField;
use App\Models\MasEmployeeJob;
use App\Models\MasOfficeTiming;
use Carbon\Carbon;
use App\Models\User;
use Intervention\Image\Facades\Image as Image;

/**
 * Helper functions
 */

use Illuminate\Support\Facades\DB;

if (!function_exists('uploadImageToDirectory')) {
    /**
     * @param $file instance of uploaded file from form
     * @param $path original file path    e.g. images/uploads/profile_pic/
     * @return string  file path to be saved in database
     *
     */
    function uploadImageToDirectory($file, $path)
    {

        $randomString = str_random(8);
        $fileName = time() . '-' . $randomString . '.' . $file->getClientOriginalExtension();
        $imageDestination = $path . $fileName;
        $file->move($path, $fileName);
        return $imageDestination;
    }
}

if (!function_exists('get_image')) {
    /**
     * @param $path original file path
     * @param $width expected width in pixels
     * @param int $height expected height in pixes
     * @param bool $crop
     * @return string
     *
     * Generate an image from original file as per required image size
     * Eg: <img src="{{ get_image('path/to/original/image.jpg', 100, 150) }}">
     *
     */
    function get_image($path, $width, $height = 0, $crop = true)
    {
        //if original path not found, return not found image
        if (!file_exists($path)) {
            $path = 'img/no-image.png';
        }
        //construct filename
        $fileNameArray = explode('.', $path);
        $extension = '.' . array_pop($fileNameArray);
        //add width and height to file name, and add in the extension
        $fileName = implode('', array_merge($fileNameArray, ['_', $width, '_', $height, $extension]));
        //if this file exists, return it
        if (file_exists($fileName)) {
            return url($fileName);
        }
        //image not found, generate new one.
        $height = $height === 0 ? null : $height;
        $width = $width === 0 ? null : $width;
        \Intervention\Image\Facades\Image::make($path)
            ->fit($width, $height)->save($fileName);
        return url($fileName);
    }
}

if (!function_exists('delete_image')) {
    function delete_image($path)
    {
        if ($path) {
            // unlink(public_path($path)); //incase if path not found provide meaningful message to user to avoid confusion
            $decodedString = decoded_string($path);

            if ($decodedString) {
                foreach ($decodedString as $string) { //incase if path not found provide meaningful message to user to avoid confusion

                    unlink(public_path($string));
                }
            } else {
                unlink(public_path($path));
            }
        } else {
            return false;
        }
        return true;
    }
}

if (!function_exists('decoded_string')) {
    function decoded_string($string)
    {
        $decodedString = json_decode($string); // Attempt to decode the JSON string
        if ($decodedString) {
            return $decodedString;
        } else {
            return false;
        }
    }
}


if (!function_exists('comma_separated_to_array')) {
    function comma_separated_to_array($value, $separator = ',')
    {
        $values = explode($separator, $value);
        foreach ($values as $k => $v) {
            $values[$k] = trim($v);
        }
        return array_diff($values, array(""));
    }
}




if (!function_exists('convert_array_to_string')) {
    function convert_array_to_string($array, $separator)
    {
        return implode($separator, $array);
    }
}

if (!function_exists('formatSizeUnits')) {
    function formatSizeUnits($bytes)
    {
        if ($bytes > 0) {
            $unit = intval(log($bytes, 1024));
            $units = array('B', 'KB', 'MB', 'GB');

            if (array_key_exists($unit, $units) === true) {
                return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
            }
        }

        return $bytes;
    }
}

if (!function_exists('daysInMonth')) {
    /**
     * returns total days in a month
     * @param $date Date instance of a month
     */
    function daysInMonth($date)
    {
        $date = Carbon\Carbon::parse($date);
        return $date->daysInMonth;
    }
}

if (!function_exists('monthNumberToName')) {
    /**
     * Converts the month number to its name
     * @param $monthNumber integer value of the month
     */
    function monthNumberToName($monthNumber)
    {
        $monthName = date("F", mktime(0, 0, 0, $monthNumber, 1));
        return $monthName;
    }
}

if (!function_exists('dayNumberToName')) {
    /**
     * Converts the day number to its name in a given date
     * @param $date date of the month
     */
    function dayNumberToName($date)
    {
        $dayName = date('D', strtotime($date));
        return $dayName;
    }
}

if (!function_exists('fixEmployeeId')) {
    //checks the length of the employee and then attaches the E00 or E000 and so on accordingly
    function fixEmployeeId($employeeId)
    {
        if (strlen($employeeId) == 1) {
            return 'E0000' . $employeeId;
        } else if (strlen($employeeId) == 2) {
            return 'E000' . $employeeId;
        } else if (strlen($employeeId) == 3) {
            return 'E00' . $employeeId;
        } else if (strlen($employeeId) == 4) {
            return 'E0' . $employeeId;
        } else if (strlen($employeeId) == 5) {
            return 'E' . $employeeId;
        }
    }
}

if (!function_exists('invoiceNoGenerator')) {
    function invoiceNoGenerator($storeId)
    {
        $startDate = date('Y') . "-01-01";
        $endDate = date('Y') . "-12-31";
        $maxInvoiceNo = Hafele\OrderInvoice::whereBetween('invoice_date', [$startDate, $endDate])->select(DB::raw('count(id) as max_no'))->first();
        if (!(bool)$maxInvoiceNo->max_no) {
            $newInvoiceNo = 1;
        } else {
            $newInvoiceNo = (int)$maxInvoiceNo->max_no + 1;
        }
        $invoiceNo = str_pad($newInvoiceNo, 5, '0', STR_PAD_LEFT);
        return $invoiceNo;
    }
}

if (!function_exists('employeeList')) {
    function employeeList()
    {
        return DB::table('mas_employees as t1')->selectRaw("t1.id, concat(t1.username, ' - ', t1.title, ' ', t1.name) as name")->whereNotIn('t1.id', [1, 2])->get();
    }
}

if (!function_exists('modifyFormRequest')) {
    function modifyFormRequest($formData)
    {
        foreach ($formData as $key => $value) {
            // If the value is an array, call the function recursively
            if (is_array($value)) {
                $formData[$key] = modifyFormRequest($value);
                // Remove the key if the array is empty after cleaning
                if (empty($formData[$key])) {
                    unset($formData[$key]);
                }
            } elseif ($value === null || $value === "") {
                // Remove the key if the value is null or an empty string
                unset($formData[$key]);
            }
        }
        return $formData;
    }
}

if (!function_exists('loggedInUser')) {
    function loggedInUser()
    {
        return auth()->user()->id;
    }
}

if (!function_exists('LoggedInUserEmpIdName')) {
    function LoggedInUserEmpIdName()
    {
        return auth()->user()->username . ' - ' . auth()->user()->title . ' ' . auth()->user()->name;
    }
}

if (!function_exists('generateTransactionNumber')) {
    function generateTransactionNumber($code, $currentSequence)
    {
        //include cureent Ymd in while generating transaction number
        $datePart = now()->format('Ymd');
        // return $code . '/' . $datePart . '/' . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
        return $code . '/' . $datePart . '/' . $currentSequence + 1;
    }
}

if (!function_exists('generateTransactionNumber1')) {
    // type => respective modelType(eg: MasRequisitionType, lastTransaction is latest transaction from application model(eg: RequisitionApplication),
    // columnName is coulumn name in application table that holds transaction_number(eg:requisition_no)
    function generateTransactionNumber1($type, $lastTransaction, $columnName)
    {
        //include cureent Ymd in while generating transaction number
        if ($lastTransaction) {
            // Extract the sequence part (last part after the last slash)
            preg_match('/(\d+)$/', $lastTransaction[$columnName], $matches);
            $lastSequence = $matches ? (int) $matches[0] : 0;
            $currentSequence = $lastSequence + 1;
        } else {
            $currentSequence = 1;
        }

        $datePart = now()->format('Ymd');
        // return $code . '/' . $datePart . '/' . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
        return $type['code'] . '/' . $datePart . '/' . $currentSequence;
    }
}
if (!function_exists('loadApplicationDetails')) {
    function loadApplicationDetails($expenseApplication, $mappedModel)
    {
        $expenseApplication->verified_by = ApplicationAuditLog::where('application_type', $mappedModel)
            ->where('application_id', $expenseApplication->id)
            ->where('status', '2')
            ->select(['id', 'action_performed_by', 'created_at'])
            ->with('performedBy:id,name')
            ->first();

        $expenseApplication->approved_by = ApplicationAuditLog::where('application_type', $mappedModel)
            ->where('application_id', $expenseApplication->id)
            ->where('status', '3')
            ->select(['id', 'action_performed_by', 'created_at'])
            ->with('performedBy:id,name')
            ->first();

        $expenseApplication->rejected_by = ApplicationAuditLog::where('application_type', $mappedModel)
            ->where('application_id', $expenseApplication->id)
            ->where('status', '-1')
            ->select(['id', 'action_performed_by', 'created_at'])
            ->with('performedBy:id,name')
            ->first();

        $expenseApplication->rejectRemarks = ApplicationHistory::where('application_type', $mappedModel)
            ->where('application_id', $expenseApplication->id)
            ->value('remarks');

        return $expenseApplication;
    }
}

if (!function_exists('loggedInUserRegion')) { //loggedInUser Region name and id based on mass_office_id
    function loggedInUserRegion()
    {
        $loggedInUserId = loggedInUser();
        $loggedInUserOfficeId = MasEmployeeJob::where('mas_employee_id', $loggedInUserId)->value('mas_office_id');
        $loggedInUserRegion = DB::select(
            "select
                                            t3.mas_region_id as region_id,
                                            t3.name as region_name
                                        from mas_offices t1
                                        left join mas_dzongkhags t2 on t1.mas_dzongkhag_id = t2.id
                                        left join mas_region_locations t3 on t2.id = t3.mas_dzongkhag_id
                                        where t1.id = ?",
            [$loggedInUserOfficeId]
        );
        return $loggedInUserRegion;
    }
}

if (!function_exists('approvalHeadConditionField')) {
    function approvalHeadConditionFields($approvalHeadId, $request)
    {
        $conditionFields = MasConditionField::where('mas_approval_head_id', $approvalHeadId)->get(['id', 'name', 'has_employee_field'])->toArray();
        // dd($conditionFields);
        foreach ($conditionFields as &$field) {
            if ($request->has($field['name'])) {
                $field['value'] = $request->input($field['name']);
                // $field['value'] = $request
            } else { // Set 'value' to null if not present in the request
                $field['value'] = null;
            }
        }

        return $conditionFields;
    }
}

if (!function_exists('empDetails')) {
    function empDetails($empId)
    {
        $empDetails = User::with('empJob')->where('id', $empId)->first();
        return $empDetails;
    }
}


if (!function_exists('formatDate')) {
    function formatDate($date)
    {
        $carbonDate = Carbon::parse($date);
        $formatedDate = $carbonDate->format('Y-m-d');
        return $formatedDate;
    }
}

if (!function_exists('prepareLeaveCombination')) {
    function prepareLeaveCombination($fromDate)
    {
        $leaveApplications = LeaveApplication::where('created_by', loggedInUser())
            // ->where('status', 1)
            ->orderBy('to_date', 'desc')
            ->get();

        // Find the latest leave with a 1-day difference from the current from_date
        $latestLeave = $leaveApplications->first(function ($leave) use ($fromDate) {
            return Carbon::parse($leave->to_date)->diffInDays($fromDate) == 1;
        });

        // Return early if no latest leave is found
        if (!$latestLeave) {
            return;
        }

        // Find the second leave with a 1-day difference from the latest leave's from_date
        $secondLeave = $leaveApplications->first(function ($leave) use ($latestLeave) {
            $latestFromDate = Carbon::parse($latestLeave->from_date);
            return Carbon::parse($leave->to_date)->diffInDays($latestFromDate) == 1;
        });

        // Combine both leaves into a collection for further use
        $matchingLeaves = collect();
        if ($latestLeave) $matchingLeaves->push($latestLeave);
        if ($secondLeave) $matchingLeaves->push($secondLeave);

        return $matchingLeaves ? $matchingLeaves : [];
    }
}

if (!function_exists('getApplicationLogs')) {
    function getApplicationLogs($model, $applicationId)
    {
        $applicationLogs = ApplicationAuditLog::where(
            'application_type',
            $model
        )->where('application_id', $applicationId)->get();
        return $applicationLogs;
    }
}


if (!function_exists('prepareMail')) {
    function prepareMail($applicationModel, $applicationData, $appType, $status)
    {
        $applicationData['type'] = $appType->name;
        $approverMailContent = $applicationModel['approver_mail_content'];
        $initiatorMailContent = $applicationModel['initiator_mail_content'];
        $response = [];
        if ($status == 2) {
            $finalApproverMailContent = prepareMailContent($approverMailContent, $applicationData);
            $finalInitiatorMaleContent = prepareMailContent($initiatorMailContent, $applicationData);
            $response['approver_mail_content'] = $finalApproverMailContent;
            $response['initiator_mail_content'] = $finalInitiatorMaleContent;
        } else if ($status == 3) {
            $finalInitiatorMaleContent = prepareMailContent($initiatorMailContent, $applicationData);
            $response['initiator_mail_content'] = $finalInitiatorMaleContent;
        } else if ($status == -1) {
            $finalInitiatorMaleContent = prepareMailContent($initiatorMailContent, $applicationData);
            $response['initiator_mail_content'] = $finalInitiatorMaleContent;
        }
        return $response;
    }
}

if (!function_exists('prepareMailContent')) {
    function prepareMailContent($approverMailContent, $applicationData)
    {
        $finalContent = preg_replace_callback(
            '/\{(\w+)\}/', // Match placeholders like {key}
            function ($matches) use ($applicationData) {
                $key = $matches[1]; // Extract the key inside {}
                return $applicationData[$key] ?? $matches[0]; // Replace with value or keep original placeholder
            },
            $approverMailContent
        );
        return $finalContent;
    }
}

if (!function_exists('normalizePathForDisplay')) {
    function normalizePathForDisplay($path)
    {
        $path = stripslashes($path); // Removes escape slashes
        $path = preg_replace('/"+/', '', $path); // Removes any quotes
        $path = preg_replace('/\/+/', '/', $path); // Normalizes multiple slashes
        return $path;
    }
}

if (!function_exists('formatAmount')) {
    function formatAmount($amount, $curFLag = true)
    {
        if ($curFLag == false) {
            return number_format($amount, 2);
        }
        return 'Nu. ' . number_format($amount, 2);
    }
}

if (!function_exists('delegatedRole')) {
    function delegatedRole($userId)
    {
        $today = now()->toDateString();
        // Delegated roles
        $delegatedRole = \DB::table('delegations')
            ->where('delegatee_id', $userId)
            ->where('status', 1)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->pluck('role_id')
            ->toArray();

        return $delegatedRole;
    }
}

if (!function_exists('getDelegations')) {
    function getDelegations($roleId)
    {
        $today = now()->toDateString();

        $delegations = \DB::table('delegations')
            ->where('role_id', $roleId)
            ->where('status', 1)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->get();

        return $delegations;
    }
}

if (!function_exists('getDelegatee')) {
    function getDelegatee($delegatorId)
    {
        $delegations = \DB::table('delegations')
            ->where('delegator_id', $delegatorId)
            ->get(['delegatee_id', 'start_date', 'end_date']);

        return $delegations;
    }
}

if (!function_exists('getDelegateeRecords')) {
    function getDelegateeRecords($query, $delegatee, $modelClass, $statuses)
    {
        foreach ($delegatee as $user) {
            $query->orWhereHas('audit_logs', function ($q) use ($user, $modelClass, $statuses) {
                $q->where('application_type', $modelClass)
                    ->where('action_performed_by', $user->delegatee_id)
                    ->whereIn('status', $statuses);
            });
        }
    }
}

if (!function_exists('getDeleagteeList')) {
    function getDeleagteeList($roleId)
    {
        $employees = [];
        if ($roleId == DEPARTMENT_HEAD) {
            $departmentId = MasEmployeeJob::where('mas_emloyee_id', auth()->user()->id)->value('mas_department_id');
            $employees = User::whereHas('empJob', function ($query) use ($departmentId) {
                $query->where('mas_section_id', $departmentId);
            })
                ->get()->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'emp_id_name' => $user->emp_id_name, // uses accessor safely
                    ];
                });
        } else if ($roleId == MANAGING_DIRECTOR) {
            $employees = User::whereHas('roles', function ($query) {
                $query->whereIn('roles.id', [DEPARTMENT_HEAD, IMMEDIATE_HEAD]);
            })
                ->get()->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'emp_id_name' => $user->emp_id_name
                    ];
                });
        } else {

            $sectionId = MasEmployeeJob::where('mas_employee_id', auth()->user()->id)->value('mas_section_id');
            $employees = User::whereHas('empJob', function ($query) use ($sectionId) {
                $query->where('mas_section_id', $sectionId);
            })
                ->get()->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'emp_id_name' => $user->emp_id_name, // uses accessor safely
                    ];
                });
        }
        return $employees;
    }
}

if(!function_exists('getMappedMonth')){
    function getMappedMonth(){
        $currentMonthStr = strtoupper(Carbon::now()->format('M')); // 'JUN'
        $currentMonthNum = config('global.months')[$currentMonthStr];
        return $currentMonthNum;
    }
}

if(!function_exists('getEffectiveOfficeTiming')){
    function getEffectiveOfficeTiming($userData){
        $currentMonthNum = getMappedMonth();
        $officeTiming = [];
        $office = $userData->empJob->office;
        $officeTiming['longitude'] = $office->longitude;
        $officeTiming['latitude'] = $office->latitude;
        // $officeTiming['raidus'] = $office->raidus . ' ' . config('global.raidus_unit');
        $officeTiming['raidus'] = $office->raidus;
        $officeTiming['attendance_buffer_mins'] = config('global.attendance_buffer_mins');
        if (isset($userData['employeeInShifts']) && isset($userData['employeeInShifts'][0]['departmentShift'])) {
            $officeTiming['start_time'] = $userData['employeeInShifts'][0]['departmentShift']->start_time;
            $officeTiming['end_time'] = $userData['employeeInShifts'][0]['departmentShift']->end_time;
            $officeTiming['shift_name'] = $userData['employeeInShifts'][0]['departmentShift']['shiftType']->name;
        }else{
            $defaultOfficeTiming = MasOfficeTiming::where(function ($query) use ($currentMonthNum) {
                $query->whereRaw('? BETWEEN start_month AND end_month', [$currentMonthNum])
                    ->orWhereRaw('start_month > end_month AND (? >= start_month OR ? <= end_month)', [$currentMonthNum, $currentMonthNum]);
            })->select('start_time', 'end_time')->first();

            $officeTiming['start_time'] = $defaultOfficeTiming->start_time;
            $officeTiming['end_time'] = $defaultOfficeTiming->end_time;
            $officeTiming['shift_name'] = 'Regular';
        }

        return $officeTiming;
    }
}



