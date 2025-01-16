<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pembayaran;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung jumlah siswa berdasarkan jenis kelamin
        $siswa_laki_laki = DB::table('siswa')->where('jenis_kelamin', 'Laki-laki')->count();
        $siswa_perempuan = DB::table('siswa')->where('jenis_kelamin', 'Perempuan')->count();

        // Menghitung total nominal pembayaran untuk tahun ini
        $total_nominal = Pembayaran::whereYear('tanggal_bayar', date('Y'))
            ->sum('jumlah_bayar'); // Menggunakan kolom 'jumlah_bayar' di tabel 'pembayaran'

        // Menghitung total nominal per semester (Ganjil dan Genap)
        $total_nominal_ganjil = Pembayaran::whereYear('tanggal_bayar', date('Y'))
            ->where('semester_bayar', 'Ganjil')
            ->sum('jumlah_bayar');
            
        $total_nominal_genap = Pembayaran::whereYear('tanggal_bayar', date('Y'))
            ->where('semester_bayar', 'Genap')
            ->sum('jumlah_bayar');

        // Mengembalikan data ke view
        return view('admin.dashboard', [
            'total_siswa' => DB::table('siswa')->count(),
            'total_kelas' => DB::table('kelas')->count(),
            'total_admin' => DB::table('model_has_roles')->where('role_id', 1)->count(),
            'total_petugas' => DB::table('petugas')->count(),
            'siswa_laki_laki' => $siswa_laki_laki,
            'siswa_perempuan' => $siswa_perempuan,
            'total_nominal' => $total_nominal,
            'total_nominal_ganjil' => $total_nominal_ganjil,
            'total_nominal_genap' => $total_nominal_genap,
        ]);
    }
}
