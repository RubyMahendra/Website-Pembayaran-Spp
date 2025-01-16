<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;

class PaymentDueDateReminder extends Mailable
{
    use Queueable;

    protected $pembayaran; // Menggunakan objek pembayaran untuk mengakses user dan tanggal jatuh tempo

    /**
     * Constructor untuk menerima data pembayaran.
     *
     * @param  \App\Models\Pembayaran  $pembayaran
     * @return void
     */
    public function __construct($pembayaran)
    {
        $this->pembayaran = $pembayaran;
    }

    /**
     * Membangun email.
     *
     * @return $this
     */
    public function build()
    {
        // Pastikan ada user dan tanggal jatuh tempo
        $user = $this->pembayaran->user;
        $dueDate = $this->pembayaran->due_date;

        return $this->subject('Reminder: Payment Due Date')
                    ->view('emails.payment_due_reminder') // Nama view yang digunakan untuk email
                    ->with([
                        'user' => $user,
                        'dueDate' => $dueDate,
                    ]);
    }
}
