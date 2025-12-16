<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifikasiKekuranganSyarat extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $judul;
    public $pesan;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $judul, string $pesan)
    {
        $this->user = $user;
        $this->judul = $judul;
        $this->pesan = $pesan;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->judul . ' - SIMAGANG',
            from: new Address(
                config('mail.from.address', 'noreply@bbkb-yogyakarta.go.id'),
                'SIMAGANG - Admin'
            ),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.notifikasi-kekurangan-syarat',
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
