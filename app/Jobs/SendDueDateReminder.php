<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Pembayaran;
use App\Mail\PaymentDueDateReminder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendDueDateReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pembayaran;

    /**
     * Membuat instance job baru.
     *
     * @param  \App\Models\Pembayaran  $pembayaran
     * @return void
     */
    public function __construct(Pembayaran $pembayaran)
    {
        $this->pembayaran = $pembayaran;
    }

    /**
     * Menjalankan job.
     *
     * @return void
     */
    public function handle()
    {
        // Mengakses pembayaran yang telah diterima pada konstruktor
        $pembayaran = $this->pembayaran;

        // Pastikan user ada dan emailnya valid sebelum mengirim email
        if ($pembayaran->user && filter_var($pembayaran->user->email, FILTER_VALIDATE_EMAIL)) {
            try {
                // Kirim email pengingat jatuh tempo
                Mail::to($pembayaran->user->email)->send(new PaymentDueDateReminder($pembayaran));
            } catch (\Exception $e) {
                // Mencatat error jika pengiriman email gagal
                Log::error('Gagal mengirim pengingat jatuh tempo untuk pembayaran ID ' . $pembayaran->id . ': ' . $e->getMessage());
            }
        } else {
            Log::warning('Email user tidak ditemukan atau tidak valid untuk pembayaran ID ' . $pembayaran->id);
        }
    }
}
