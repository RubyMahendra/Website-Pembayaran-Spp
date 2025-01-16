<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PemberitahuanEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $nama_lengkap;
    public $email;
    public $kendala;
    public $deskripsi;

    /**
     * Create a new message instance.
     *
     * @param $nama_lengkap
     * @param $email
     * @param $kendala
     * @param $deskripsi
     */
    public function __construct($nama_lengkap, $email, $kendala, $deskripsi)
    {
        $this->nama_lengkap = $nama_lengkap;
        $this->email = $email;
       $this->kendala = $kendala;
        $this->deskripsi = $deskripsi;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Pemberitahuan: ' . $this->kendala)
                    ->view('emails.notifemail')  // Pastikan view email ada
                    ->with([
                        'nama_lengkap' => $this->nama_lengkap,
                        'kendala' => $this->kendala,
                        'deskripsi' => $this->deskripsi,
                    ]);
    }
}
