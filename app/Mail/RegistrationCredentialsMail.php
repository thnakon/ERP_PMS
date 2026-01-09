<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $registrantName;
    public string $businessName;
    public string $adminEmail;
    public string $staffEmail;
    public string $password;

    /**
     * Create a new message instance.
     */
    public function __construct(
        string $registrantName,
        string $businessName,
        string $adminEmail,
        string $staffEmail,
        string $password
    ) {
        $this->registrantName = $registrantName;
        $this->businessName = $businessName;
        $this->adminEmail = $adminEmail;
        $this->staffEmail = $staffEmail;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'ข้อมูลสำหรับเข้าสู่ระบบ - Oboun ERP',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.registration-credentials',
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
