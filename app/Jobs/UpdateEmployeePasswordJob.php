<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateEmployeePasswordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $employee;

    /**
     * Create a new job instance.
     */
    public function __construct($employee)
    {
        $this->employee = $employee;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            // Generate plain password
            $plainPassword = bcrypt(date('Ymd', strtotime($this->employee->dob)) . $this->employee->employee_id);

            // Update password in the database
            DB::table('mas_employees')
                ->where('id', $this->employee->id)
                ->update(['password' => $plainPassword]);
        } catch (\Exception $e) {
            Log::error("Failed to update password for {$this->employee->username} -> {$this->employee->email}: {$e->getMessage()}");
        }
    }
}
