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

class NotifikasiStatusPermohonan extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $permohonan;
    public $statusBaru;
    public $statusLama;
    public $alasan;
    public $catatanRevisi;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, PermohonanMagang $permohonan, string $statusBaru, string $statusLama = null, string $alasan = null, string $catatanRevisi = null)
    {
        $this->user = $user;
        $this->permohonan = $permohonan;
        $this->statusBaru = $statusBaru;
        $this->statusLama = $statusLama;
        $this->alasan = $alasan;
        $this->catatanRevisi = $catatanRevisi;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'Notifikasi Status Permohonan Magang - SIMAGANG';
        
        // Custom subject berdasarkan status
        switch ($this->statusBaru) {
            case 'Diverifikasi':
                $subject = 'Dokumen Anda Telah Diverifikasi - SIMAGANG';
                break;
            case 'Diterima':
                $subject = 'Selamat! Permohonan Magang Anda Diterima - SIMAGANG';
                break;
            case 'Ditolak':
                $subject = 'Update Status Permohonan Magang Anda - SIMAGANG';
                break;
            case 'Revisi':
                $subject = 'Permohonan Anda Memerlukan Revisi - SIMAGANG';
                break;
        }
        
        return new Envelope(
            subject: $subject,
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
            view: 'emails.notifikasi-status-permohonan',
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
