<?php

use App\Models\MasConditionField;
use App\Models\MasEmployeeJob;
use App\Models\User;
use Intervention\Image\Facades\Image as Image;
/**
 * Helper functions
 */
Use Illuminate\Support\Facades\DB;

if (!function_exists('uploadImageToDirectory')) {
    /**
     * @param $file instance of uploaded file from form
     * @param $path original file path    e.g. images/uploads/profile_pic/
     * @return string  file path to be saved in database
     *
     */
    function uploadImageToDirectory($file, $path)
    {
        $randomString=str_random(8);
        $fileName = time() . '-' . $randomString . '.' . $file->getClientOriginalExtension();
        $imageDestination = $path.$fileName;
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
    function get_image ($path, $width, $height = 0, $crop = true)
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
        // Check if the file exists before trying to delete
        if (file_exists($path) && is_file($path)) {
            // Attempt to delete the original file
            if (!@unlink($path)) {
                // Log error or handle failure if needed
                return false;
            }
        }
        //delete the original file
        // @unlink($path);
        //delete other size variations of the same file
        $fileNameArray = explode('.', $path);
        $extension = array_pop($fileNameArray);
        //variables will be in the form of  example-file-blah-blah_100_20.jpg
        //the path will be example-file-blah-blah.jpg
        //first remove extension, replace extension with blank
        //then add the wildcard pattern
        $pattern = str_replace(".$extension", "", $path) . "_*_*" . ".$extension";
        foreach (glob($pattern) as $file) {
            @unlink($file);
        }
        return true;
    }
}
// app/Helpers/ImageHelper.php

if (!function_exists('updateImage')) {
    function updateImage($request, $employee, $fieldName, $directory)
    {
        // Check if the employee already has an image and delete the old one
        if ($employee->$fieldName) {
            $oldImagePath = public_path($employee->$fieldName);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath); // Delete the old image file
            }
        }

        // Check if a new image file is uploaded
        if ($request->hasFile($fieldName)) {
            $image = $request->file($fieldName);

            // Generate a new file name
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path($directory), $imageName); // Move the file to the desired directory

            // Update the employee's record with the new image path
            $employee->$fieldName = '/' . $directory . '/' . $imageName;
            $employee->save(); // Save the updated record
        }
    }
}


if (!function_exists('comma_separated_to_array')) {
    function comma_separated_to_array($value, $separator = ',')
    {
        $values = explode($separator, $value);
        foreach($values as $k => $v) {
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
        if ($bytes > 0)
        {
            $unit = intval(log($bytes, 1024));
            $units = array('B', 'KB', 'MB', 'GB');

            if (array_key_exists($unit, $units) === true)
            {
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
            return 'E0000'.$employeeId;
        } else if (strlen($employeeId) == 2) {
            return 'E000'.$employeeId;
        } else if (strlen($employeeId) == 3) {
            return 'E00'.$employeeId;
        } else if (strlen($employeeId) == 4) {
            return 'E0'.$employeeId;
        } else if (strlen($employeeId) == 5) {
            return 'E'.$employeeId;
        }
    }
}

if (!function_exists('invoiceNoGenerator')) {
    function invoiceNoGenerator($storeId){
        $startDate = date('Y')."-01-01";
        $endDate = date('Y')."-12-31";
        $maxInvoiceNo = Hafele\OrderInvoice::whereBetween('invoice_date', [$startDate, $endDate])->select(DB::raw('count(id) as max_no'))->first();
        if(!(bool)$maxInvoiceNo->max_no){
            $newInvoiceNo = 1;
        }else{
            $newInvoiceNo = (int)$maxInvoiceNo->max_no + 1;
        }
        $invoiceNo = str_pad($newInvoiceNo, 5, '0', STR_PAD_LEFT);
        return $invoiceNo;
    }
}

if(!function_exists('employeeList')){
    function employeeList(){
        return DB::table('mas_employees as t1')->selectRaw("t1.id, concat(t1.username, ' - ', t1.name) as name")->get();
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

if(!function_exists('loggedInUser')){ 
    function loggedInUser(){
        return auth()->user()->id;
    }
}

if(!function_exists('LoggedInUserEmpIdName')) {
    function LoggedInUserEmpIdName() {
        $user = User::find(loggedInUser());
        return $user->emp_id_name;
    }
}

if(!function_exists('generateTransactionNumber')){
    function generateTransactionNumber($code, $nextSequence){
        //include cureent Ymd in while generating transaction number
        $datePart = now()->format('Ymd');
        //if next sequence is not there then by default it will be 0001 else incremental basis
        return $code . '|' . $datePart . '|' . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
    }
}

if(!function_exists('loggedInUserRegion')){ //loggedInUser Region name and id based on mass_office_id
    function loggedInUserRegion(){
        $loggedInUserId = loggedInUser(); 
        $loggedInUserOfficeId = MasEmployeeJob::where('mas_employee_id', $loggedInUserId)->value('mas_office_id');
        $loggedInUserRegion = DB::select(
                                        "select 
                                            t3.mas_region_id as region_id,
                                            t3.name as region_name
                                        from mas_offices t1
                                        left join mas_dzongkhags t2 on t1.mas_dzongkhag_id = t2.id
                                        left join mas_region_locations t3 on t2.id = t3.mas_dzongkhag_id
                                        where t1.id = ?", [$loggedInUserOfficeId]);
        return $loggedInUserRegion;
    }
}

if(!function_exists('approvalHeadConditionField')){
    function approvalHeadConditionFields($approvalHeadId, $request) {
        $conditionFields = MasConditionField::where('mas_approval_head_id', $approvalHeadId)->get(['id', 'name', 'has_employee_field'])->toArray();
        foreach($conditionFields as &$field){
            if($request->has($field['name'])){
                $field['value'] = $request->input($field['name']);
                // $field['value'] = $request   
            }else {// Set 'value' to null if not present in the request
                $field['value'] = null;
            }
        }
        return $conditionFields;
    }
}
