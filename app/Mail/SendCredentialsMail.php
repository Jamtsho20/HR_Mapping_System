<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;



    public $employee; // Additional dynamic message
    public $password; // Additional dynamic message

    public function __construct($employee, $password)
    {


        $this->employee = $employee;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'User Credentials',
        );
    }

    /**
     * Get the message content definition.
     */

    public function content(): Content
    {
        // dd($this->employee);
        return new Content(
            markdown: 'emails.employee-credentials',
            with: ['employee' => $this->employee,'password' => $this->password]
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
