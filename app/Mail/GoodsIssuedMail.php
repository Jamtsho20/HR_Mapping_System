<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GoodsIssuedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $employee;
    protected $issuedBy;
    protected $requisitionNo;
    protected $issuedDate;
    protected $emailContent;
    protected $emailSubject;

    /**
     * Create a new message instance.
     */
    public function __construct($employee,  $requisitionNo)
    {
        // Get details of the employee receiving the goods
        $this->employee = $employee
            ? ($employee->title . ' ' . $employee->name)
            : 'Unknown Employee';


        $this->requisitionNo = $requisitionNo;
        $this->issuedDate = now()->format('Y-m-d H:i');

        // Email subject
        $this->emailSubject = 'Goods Issued Notification';

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
            markdown: 'emails.goods_issued',
            with: [
                'employee' => $this->employee,
                'requisitionNo' => $this->requisitionNo,
                'issuedDate' => $this->issuedDate
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
