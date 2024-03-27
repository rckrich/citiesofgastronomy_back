<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Mailables\Address;

class mailUserCreate extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $email;
    public $token;
    public $token2;
    public $expirationMail;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $email, $token, $expirationMail)
    {
        $this->userName = $name;
        $this->$email = $email;
        $this->$token = $token;
        $token2 = $token;
        $this->$token2 = $token;
        $this->$expirationMail = $expirationMail;
        Log::info($token);
        Log::info($this->token);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('app.mailFrom'), 'Cities of Gastronomy'),
            subject: 'Administrator Create',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'userCreate',
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
