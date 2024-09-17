<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaySlipMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $paySlipFile;
    protected $employeeName;
    protected $monthFriendly;
    /**
     * Create a new message instance.
     */
    public function __construct($paySlipFile,$employeeName,$monthFriendly)
    {
        //
        $this->paySlipFile = $paySlipFile;
        $this->employeeName = $employeeName;
        $this->monthFriendly = $monthFriendly;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pay Slip for the month of '.$this->monthFriendly,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.payslip',
            with: [
                'employeeName'=> $this->employeeName,
                'monthFriendly'=> $this->monthFriendly,
                'paySlipFile'=> $this->paySlipFile,
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
        return [
            Attachment::fromPath($this->paySlipFile)->as($this->employeeName.".pdf"),
        ];
    }
}
