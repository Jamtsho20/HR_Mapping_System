<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeaveEncashmentMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    use Queueable, SerializesModels;

    public $employee;
    public $leaveBalance;

    /**
     * Create a new message instance.
     *
     * @param $employee
     * @param $leaveBalance
     */
    public function __construct($employee, $leaveBalance)
    {
        $this->employee = $employee;
        $this->leaveBalance = $leaveBalance;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Leave Encashment Notification')
                    ->view('emails.leave_encashment');
    }
}
