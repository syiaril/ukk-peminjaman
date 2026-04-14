<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengingatJatuhTempo extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param mixed $peminjaman Object data peminjaman
     * @param string $tipe 'besok' atau 'hari_ini'
     */
    public function __construct(
        public $peminjaman,
        public string $tipe
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->tipe === 'besok'
            ? '⚠️ Pengingat: Pengembalian Barang Besok'
            : '🔴 Jatuh Tempo Hari Ini: Segera Kembalikan Barang';

        return new Envelope(subject: $subject);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pengingat-jatuh-tempo',
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
