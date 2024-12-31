<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationForwardedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // protected $requestingUserId;
    protected $approver;
    protected $emailContent;
    protected $emailSubject;
    /**
     * Create a new message instance.
     */
    public function __construct($requestingUserId, $approvingUserId, $emailContent, $emailSubject)
    {
        $initiatorDetails = User::with('empJob')->where('id', $requestingUserId)->first();
        $initiator = $initiatorDetails['title'] . ' ' . $initiatorDetails['name'] . ', ' 
                    . $initiatorDetails->empJob->designation->name . ', ' 
                    . $initiatorDetails->empJob->section->name ?? '' 
                    . $initiatorDetails->empJob->department->name;

        $this->approver = User::where('id', $approvingUserId)->first();
        $this->emailContent = $initiator . ' ' . $emailContent;
        $this->emailSubject = $emailSubject;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
            // subject: 'Application Forwarded Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.application-forwarded',
            with: [
                'approver' => $this->approver['title'] . ' ' . $this->approver['name'],
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
