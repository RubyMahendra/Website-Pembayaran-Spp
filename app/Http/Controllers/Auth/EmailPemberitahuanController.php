<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller; 
use App\Mail\PemberitahuanEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailPemberitahuanController extends Controller
{
    public function send(Request $request)
    {
        // Validasi inputan
        $request->validate([
            'nama_lengkap' => 'required',
            'email' => 'required|email',
            'kendala' => 'required', // Pastikan konsisten dengan nama field formulir
            'deskripsi' => 'required_if:use_default,0',
        ]);

        // Kirim email ke alamat yang dimasukkan
        try {
            Mail::to($request->email)->send(new PemberitahuanEmail(
                $request->nama_lengkap,
                $request->email,
                $request->kendala, // Pastikan ini sesuai dengan parameter yang dikirim
                $request->deskripsi
            ));
        } catch (\Exception $e) {
            Log::error('Error sending email: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Terjadi kesalahan dalam pengiriman email.']);
        }

        // Tambahkan session flash untuk status sukses
        session()->flash('status', 'Email pemberitahuan sudah dikirim!');

        // Redirect kembali dengan status flash
        return redirect()->back();
    }
    public function showPemberitahuanEmail($nama_lengkap, $deskripsi)
{
    return view('pemberitahuan', [
        'nama_lengkap' => $nama_lengkap,
        'deskripsi' => $deskripsi
    ]);
}

}
