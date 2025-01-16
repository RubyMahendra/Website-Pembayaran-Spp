<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\PengajuanKendala;

class PengajuanKendalaController extends Controller
{
    public function index()
{
    // Ambil semua data pengajuan kendala dari database
    $pengajuanKendala = PengajuanKendala::all(); // singular

    // Kirim data ke view untuk ditampilkan
    return view('admin.pengajuankendala.index', compact('pengajuanKendala')); // singular
}

    
    



    // Fungsi untuk menyimpan data dan mengirim email
    public function kirimPengajuan(Request $request)
{
    $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        'email_sekolah' => 'required|email',
        'email_pengguna' => 'required|email',
        'kendala' => 'required|string',
        'deskripsi' => 'nullable|string',
    ]);

    // Data yang akan disimpan ke database
    $data = [
        'nama_lengkap' => $request->nama_lengkap,
        'email_sekolah' => $request->email_sekolah,
        'email_pengguna' => $request->email_pengguna,
        'kendala' => $request->kendala,
        'deskripsi' => $request->deskripsi,
        'status' => 'Tahap Pengecekan', // Status default
    ];

    try {
        // Simpan data ke database
        PengajuanKendala::create($data);

        // Kirim email ke pengguna
        Mail::send('emails.pengajuan_kendala', $data, function ($message) use ($data) {
            $message->to($data['email_pengguna'])  // Kirim ke email pengguna
                ->cc($data['email_sekolah'])      // CC ke email petugas/sekolah
                ->subject('Pengajuan Kendala dari ' . $data['nama_lengkap']);
        });

        return back()->with('status', 'Pengajuan kendala berhasil dikirim dan disimpan.');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Gagal memproses pengajuan. Silakan coba lagi.']);
    }
}



    // Fungsi untuk memperbarui status pengajuan
    public function updateStatus(Request $request, $id)
    {
        // Validasi status yang diizinkan
        $allowedStatuses = ['Tahap Pengecekan', 'Selesai'];
        $status = $request->input('status');
    
        if (!in_array($status, $allowedStatuses)) {
            return response()->json([
                'success' => false,
                'message' => 'Status tidak valid. Pilih status yang sesuai.'
            ], 400);
        }
    
        // Cari data pengajuan berdasarkan ID
        $pengajuan = PengajuanKendala::findOrFail($id);
    
        // Periksa apakah status sama dengan status saat ini
        if ($pengajuan->status === $status) {
            return response()->json([
                'success' => false,
                'message' => 'Status sudah ' . $status . ', tidak ada perubahan.'
            ], 200);
        }
    
        // Perbarui status di database
        $pengajuan->update(['status' => $status]);
    
        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui menjadi ' . $status . '.'
        ], 200);
    }
    
    




}