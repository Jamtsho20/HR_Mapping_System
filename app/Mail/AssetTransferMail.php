<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;

class AssetTransferMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $requestingUser;
    protected $receivingUser;
    protected $emailSubject;
    protected $emailContent;

    /**
     * Create a new message instance.
     */
    public function __construct($requestingUserId, $receivingUserId, $emailSubject, $type)
    {

        $this->receivingUser = User::with('empJob')->where('id', $receivingUserId)->first();


        $this->requestingUser = $requestingUserId;
        $this->emailSubject = $emailSubject;

        $this->emailContent = 'An '.$type.' from '.$this->receivingUser->title.' '.$this->receivingUser->name.', '.$this->receivingUser->empJob->section->name.', '.$this->receivingUser->empJob->department->name.' has been initiated and assigned to you. Please acknowledge the transfer upon receiving the asset';

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
            markdown: 'emails.asset_transfer',
            with: [
                'requestingUser' => $this->requestingUser,
                'receivingUser' => $this->receivingUser,
                'emailContent' => $this->emailContent
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
