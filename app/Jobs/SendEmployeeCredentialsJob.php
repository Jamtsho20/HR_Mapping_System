<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendCredentialsMail;

class SendEmployeeCredentialsJob implements ShouldQueue
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
            // Generate password
            // $password = date('Ymd', strtotime($this->employee->dob)) . $this->employee->employee_id;



            if (!empty($this->employee->email)) {
            Mail::to($this->employee?->email)->send(new SendCredentialsMail($this->employee, $this->employee->password));

            }



            // Mark as email sent
            $this->employee->registered_email_sent = 1;
            $this->employee->save();
        } catch (\Exception $e) {
            \Log::error("Failed to send email to {$this->employee->email}: {$e->getMessage()}");
        }
    }
}
