<?php

namespace App\Mail;

use App\Models\User;
use App\Models\PermohonanMagang;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SuratKerjaTersedia extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $permohonan;
    public $downloadUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, PermohonanMagang $permohonan)
    {
        $this->user = $user;
        $this->permohonan = $permohonan;
        $this->downloadUrl = route('download.sk');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Surat Kerja (SK) Tersedia - SIMAGANG',
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
            view: 'emails.surat-kerja-tersedia',
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
