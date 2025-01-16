<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentReminder extends Notification
{
    public $pembayaran;

    public function __construct($pembayaran)
    {
        $this->pembayaran = $pembayaran;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $dueDate = \Carbon\Carbon::parse($this->pembayaran->due_date); // Gunakan $this->pembayaran
        $currentDate = now();

        if ($currentDate->lessThan($dueDate->subDays(15))) {
            $message = "Halo, pembayaran Anda akan jatuh tempo pada: " . $dueDate->toFormattedDateString();
            $subMessage = "Segera lakukan pembayaran sebelum batas waktu untuk menghindari denda.";
        } else {
            $message = "Pengingat terakhir! Pembayaran Anda akan jatuh tempo pada: " . $dueDate->toFormattedDateString();
            $subMessage = "Mohon segera selesaikan pembayaran sebelum tanggal jatuh tempo.";
        }

        return (new MailMessage)
            ->subject('Pengingat Jatuh Tempo Pembayaran')
            ->line($message)
            ->action('Bayar Sekarang', url('/payment/' . $this->pembayaran->id)) // Gunakan $this->pembayaran
            ->line($subMessage);
    }
}
