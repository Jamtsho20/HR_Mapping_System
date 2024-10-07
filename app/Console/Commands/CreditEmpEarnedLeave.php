<?php

namespace App\Console\Commands;

use App\Models\EmployeeLeave;
use App\Models\MasLeaveType;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreditEmpEarnedLeave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'credit-emp-earned-leave:monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Credit earned leave monthly';

    
    private $leaveAmount = 2.5;
    private $regularEmpId; //refer mas_employment_types tbl
    private $probationalEmpId; //refer mas_employment_types tbl

    public function __construct()
    {
        parent::__construct();
        $this->regularEmpId = config('global.regular_emp_type_id'); // Refer to the regular employee type ID
        $this->probationalEmpId = config('global.probational_emp_type_id'); // Refer to the regular employee type ID
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();
        try{
            $leaveType = MasLeaveType::where('name', 'Earned Leave')->first();
            $employees = User::with(['empJob' => function($query) use($leaveType){
                if($leaveType->applicable_to == 1){//for regular only
                    $query->where('mas_employment_type_id', $this->regularEmpId);
                }else if($leaveType->applicable_to == 0){//for probation only
                    $query->where('mas_employment_type_id', $this->probationalEmpId);
                }else{ // for both regular and probation
                    $query->whereIn('mas_employment_type_id', [$this->regularEmpId, $this->probationalEmpId]);
                }
            }])->where('status', 1)->where('is_active', 1)->get();

            //insert or update based on presence of record 
            foreach ($employees as $employee){
                $employeeLeave = EmployeeLeave::where('mas_employee_id', $employee->id)->where('mas_leave_type_id', $leaveType->id)->first();
                if ($employeeLeave) {
                    // Update existing record
                    $employeeLeave->current_entitlement += $this->leaveAmount;
                    $employeeLeave->closing_balance += $this->leaveAmount;
                    $employeeLeave->updated_by = $employee->created_by;
                    $employeeLeave->save();
                } else {
                    // Insert new record
                    EmployeeLeave::create([
                        'mas_employee_id' => $employee->id,
                        'mas_leave_type_id' => $leaveType->id,
                        'opening_balance' => 0,
                        'current_entitlement' => $this->leaveAmount,
                        'leaves_availed' => 0,
                        'closing_balance' => $this->leaveAmount,
                        'created_by' => $employee->created_by
                    ]);
                }
            }
            DB::commit();
            $this->info('Earned leave successfully credited to all employees.');
        }catch(\Exception $e){
            DB::rollback();
            $this->error('Failed to credit earned leave: ' . $e->getMessage());
        }
    }
}
