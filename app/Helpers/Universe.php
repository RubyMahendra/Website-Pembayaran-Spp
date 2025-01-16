<?php 

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\Pembayaran;
use App\Models\User;
use App\Models\Petugas;
use App\Models\Siswa;

class Universe
{	
	public static function petugas()
	{
		return Petugas::where('user_id', Auth::user()->id)->first();
	}

	public static function siswa()
	{
		return Siswa::where('user_id', Auth::user()->id)->first(); 
	}

	public static function semesterAll()
{
    return collect([
        [
            'nama_semester' => 'Semester Ganjil',
            'kode_semester' => 'Ganjil',
        ],
        [
            'nama_semester' => 'Semester Genap',
            'kode_semester' => 'Genap',
        ],
    ]);
}


	// cek status pembayaran (diakses oleh siswa)
	public static function statusPembayaranSemester($bulan, $spp_tahun)
	{
		$siswa = Siswa::where('user_id', Auth::user()->id)
            ->first();

	    $pembayaran = Pembayaran::where('siswa_id', $siswa->id)
	        ->where('tahun_bayar', $spp_tahun)
	        ->oldest()
	        ->pluck('semester_bayar')->toArray();


	    foreach ($pembayaran as $key => $bayar) {
	    	if ($bayar == $bulan) {
	    		return "DIBAYAR";
	    	}
	    }

	    // jika pembayaran dibulan tertentu bulan belum dibayar
	    return "BELUM DIBAYAR";
	}


	// cek status pembayaran (diakses oleh petugas)
	public static function statusPembayaran($siswa_id, $tahun, $bulan)
	{
	    $pembayaran = Pembayaran::where('siswa_id', $siswa_id)
	        ->where('tahun_bayar', $tahun)
	        ->oldest()
	        ->pluck('semester_bayar')->toArray();

	    foreach ($pembayaran as $key => $bayar) {
	    	if ($bayar == $bulan) {
	    		return "DIBAYAR";
	    	}
	    }

	    // jika pembayaran dibulan tertentu bulan belum dibayar
	    return "BELUM DIBAYAR";
	}
}