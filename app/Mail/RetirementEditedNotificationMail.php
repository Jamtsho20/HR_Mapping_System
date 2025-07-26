<?php

namespace App\Mail;

use App\Models\RetirementBenefit;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RetirementEditedNotificationMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $initiator;
    protected $approver;
    protected $emailContent;
    protected $emailSubject;
    public function __construct(RetirementBenefit $nomination, $approverId)
    {
        $initiatorUser = User::with('empJob')->where('id', $nomination->mas_employee_id)->first();

        $this->initiator = $initiatorUser
            ? (
                $initiatorUser->title . ' ' . $initiatorUser->name . ', '
                . ($initiatorUser->empJob->designation->name ?? '') . ', '
                . ($initiatorUser->empJob->section->name ?? '') . ', '
                . ($initiatorUser->empJob->department->name ?? '')
            )
            : 'Unknown Employee';

        $approver = User::with('empJob')->find($approverId);
        $this->approver = $approver;

        $this->emailSubject = 'Retirement Benefit Nomination Application Edited – Please Review';

        $this->emailContent = $this->initiator . ' has edited their Retirement Benefit Nomination application. Please review the changes and take appropriate action.';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.retirement.edited',
            with: [
                'approverName' => $this->approver ? $this->approver->title . ' ' . $this->approver->name : 'Approver',
                'emailContent' => $this->emailContent,
                'reviewUrl' => route('retirement-benefit-nomination.show', $this->approver->id ?? 1), // Adjust as needed
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
