<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PembayaranNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $pembayaran;

    /**
     * Create a new message instance.
     */
    public function __construct($pembayaran)
    {
        $this->pembayaran = $pembayaran;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Notifikasi Pembayaran')
            ->view('emails.pembayaran-notification')
            ->with('pembayaran', $this->pembayaran);
    }
}

