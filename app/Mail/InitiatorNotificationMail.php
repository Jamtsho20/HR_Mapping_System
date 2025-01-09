<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InitiatorNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    protected $initiator;
    protected $emailSubject;
    protected $emailContent;
    public function __construct($requestingUserId, $emailSubject, $emailContent)
    {
        $initiatorDetail = User::where('id', $requestingUserId)->first(); 
        $this->initiator = $initiatorDetail['title'] . ' ' . $initiatorDetail['name']; 
        $this->emailSubject = $emailSubject;
        $this->emailContent = $emailContent;
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
            markdown: 'emails.initiator-notification',
            with: [
                'initiator' => $this->initiator,
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
