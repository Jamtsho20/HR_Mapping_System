<?php

namespace App\Mail;

use App\Models\SifaRegistration;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SifaEditedNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
   protected $initiator;
    protected $approver;
    protected $emailContent;
    protected $emailSubject;

    /**
     * Create a new message instance.
     */
    public function __construct(SifaRegistration $sifaRegistration, $approverId)
    {
        $initiatorUser = User::with('empJob')->where('id', $sifaRegistration->mas_employee_id)->first();

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

        $this->emailSubject = 'SIFA Application Edited – Please Review';

        $this->emailContent = $this->initiator . ' has edited their SIFA application. Please review the changes and take appropriate action.';
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
            markdown: 'emails.sifa.edited',
            with: [
                'approverName' => $this->approver ? $this->approver->title . ' ' . $this->approver->name : 'Approver',
                'emailContent' => $this->emailContent,
                'reviewUrl' => route('sifa-registration.show', $this->approver->id ?? 1), // Adjust as needed
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
