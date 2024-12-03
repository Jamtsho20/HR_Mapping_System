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

    public $employee;
    public $leaveBalance;

    /**
     * Create a new message instance.
     *
     * @param $employee
     * @param $leaveBalance
     */
    public function __construct($employee)
    {
        $this->employee = $employee;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Leave Encashment Eligibility Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.leave_encashment',
            with: [
                'employeeName' => $this->employee->name,
                'leaveBalance' => $this->leaveBalance,
            ]
        );
    }
}
