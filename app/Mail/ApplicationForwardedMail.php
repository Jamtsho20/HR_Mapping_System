<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationForwardedMail extends Mailable
{
    use Queueable, SerializesModels;

    // protected $requestingUserId;
    protected $approvingEmpName;
    protected $reqEmpName;
    protected $emailContent;
    protected $emailSubject;
    /**
     * Create a new message instance.
     */
    public function __construct($requestingUserId, $approvingUserId, $emailContent, $emailSubject)
    {
        $this->reqEmpName = User::where('id', $requestingUserId)->value('name');
        $this->approvingEmpName = User::where('id', $approvingUserId)->value('name');;
        $this->emailContent = $emailContent;
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
                'reqEmpName'=> $this->reqEmpName,
                'approvingEmpName' => $this->approvingEmpName,
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
