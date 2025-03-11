<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TravelApprovalMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $initiator;
    protected $approver;
    protected $gm;
    protected $emailContent;
    protected $emailSubject;

    /**
     * Create a new message instance.
     */
    public function __construct($requestingUserId, $approvingUserId, $emailSubject, $gm)
    {
        // Get details of the initiator (requesting user)
        $initiatorDetails = User::with('empJob')->where('id', $requestingUserId)->first();

        $this->initiator = $initiatorDetails
    ? (
        $initiatorDetails->title . ' ' . $initiatorDetails->name . ', '
        . ($initiatorDetails->empJob->designation->name ?? '') . ', '
        . ($initiatorDetails->empJob->section->name ?? '') . ', '
        . ($initiatorDetails->empJob->department->name ?? '')
    )
    : '';

        // Get details of the GM (General Manager)
        $gmDetails = User::with('empJob')->where('id', $gm['id'] ?? null)->first();

        $this->gm = $gmDetails
            ? ($gmDetails->title . ' ' . $gmDetails->name . ', ')
            : '';

        // Get details of the approver (approving user)
        $this->approver = User::where('id', $approvingUserId)->first();

        // Email subject
        $this->emailSubject = $emailSubject;

        // Formulating the email content safely
        if ($this->approver) {
            $this->emailContent = 'The travel authorization application applied by ' . $this->initiator
                . ' has been approved by ' . $this->approver->title . ' ' . $this->approver->name . ', '
                . ($this->approver->empJob->designation->name ?? '') . ', '
                . ($this->approver->empJob->section->name ?? '') . ', '
                . ($this->approver->empJob->department->name ?? '') . '.';
       
        } else {
            $this->emailContent = 'Approver details are missing.';
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.travel-approval-notification',
            with: [
                'gm' => $this->gm,
                'approver' => $this->approver
                    ? ($this->approver->title . ' ' . $this->approver->name)
                    : 'Unknown Approver',
                'emailContent' => $this->emailContent,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
