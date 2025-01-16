<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;  // Menambahkan import untuk User
use Illuminate\Support\Str;  // Menambahkan import untuk Str
use Illuminate\Support\Facades\Hash;  // Menambahkan import untuk Hash

class ForgotPasswordController extends Controller
{
    // Menampilkan form lupa password
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // Mengirim email reset password dan email ke admin
    public function sendResetLinkEmail(Request $request)
    {
        // Validasi inputan dari form
        $request->validate([
            'email' => 'required|email|exists:users,email' // Validasi email
        ]);

        // Ambil data email dari form
        $email = $request->input('email');
        
        // Ambil data user berdasarkan email
        $user = User::where('email', $email)->first();

        // Buat password baru
        $newPassword = Str::random(8); // Menggunakan Str::random

        // Hash password baru
        $hashedPassword = Hash::make($newPassword);

        // Update password di database
        $user->password = $hashedPassword;
        $user->save();

        // Kirim email kepada pengguna dengan data yang diisi
        Mail::to($email)->send(new ForgotPasswordRequest($user->username, $user->email, $user->password, $newPassword));

        // Kirim email kepada admin (opsional)
        // Mail::to('admin@example.com')->send(new ForgotPasswordRequest($user->username, $user->email, $user->password, $newPassword));

        // Menampilkan pesan sukses
        return back()->with('status', 'Kami telah mengirimkan informasi permintaan reset password ke email Anda.');
    }
}
