<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Spp;
use App\Models\Petugas;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Validator;
use App\Helpers\Bulan;
use Barryvdh\DomPDF\Facade as PDF;
use DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Siswa::with(['kelas'])->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btn = '<div class="row"><a href="'.route('pembayaran.bayar', $row->nisn).'"class="btn btn-primary btn-sm ml-2">
                    <i class="fas fa-money-check"></i> BAYAR
                    </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

    	return view('pembayaran.index');
    }

    public function bayar($nisn)
    {	
    	$siswa = Siswa::with(['kelas'])
            ->where('nisn', $nisn)
            ->first();

        $spp = Spp::all();

    	return view('pembayaran.bayar', compact('siswa', 'spp'));
    }

    public function spp($tahun)
    {
        $spp = Spp::where('tahun', $tahun)
            ->first();
        
        return response()->json([
            'data' => $spp,
            'nominal_rupiah' => 'Rp '.number_format($spp->nominal, 0, 2, '.'),
        ]);
    }

    public function prosesBayar(Request $request, $nisn)
{
    // Validasi input
    $request->validate([
        'jumlah_bayar' => 'required',
        'semester_bayar' => 'required|array',
        'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', // Validasi bukti pembayaran
    ]);

    // Mendapatkan data petugas berdasarkan user yang sedang login
    $petugas = Petugas::where('user_id', Auth::user()->id)->first();

    // Mendapatkan data siswa berdasarkan nisn
    $siswa = Siswa::where('nisn', $nisn)->first();

    // Mengubah nilai semester_bayar menjadi Ganjil atau Genap
    $semester_bayar = array_map(function ($semester) {
        return $semester == 1 ? 'Ganjil' : 'Genap'; // 1 => Ganjil, 2 => Genap
    }, $request->semester_bayar);

    // Mengecek apakah pembayaran untuk bulan dan tahun yang sama sudah ada
    $pembayaran = Pembayaran::whereIn('semester_bayar', $semester_bayar)
        ->where('tahun_bayar', $request->tahun_bayar)
        ->where('siswa_id', $request->siswa_id)
        ->pluck('semester_bayar')
        ->toArray();

    if (empty($pembayaran)) {
        DB::transaction(function () use ($request, $petugas, $siswa, $semester_bayar) {
            // Proses unggah bukti pembayaran
            $fileName = null;

            // Proses unggah bukti pembayaran
            $file = $request->file('bukti_pembayaran');

            // Mengecek apakah file ada
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                // Mengganti penamaan file sesuai format yang diinginkan
                $fileName = 'pembayaranspp_' . implode('_', $semester_bayar) . '_' . 
                            $request->nisn . '_' . 
                            str_replace(' ', '_', $siswa->nama_siswa) . '_' . 
                            str_replace(' ', '_', $siswa->kelas->nama_kelas) . '.' . 
                            $file->getClientOriginalExtension();

                // Simpan file ke storage
                $file->storeAs('public/bukti_pembayaran', $fileName);
            } else {
                Log::error('Bukti pembayaran tidak ditemukan dalam request.');
            }

            // Simpan data pembayaran ke database
            foreach ($semester_bayar as $bulan) {
                Pembayaran::create([
                    'kode_pembayaran' => 'SPPR' . Str::upper(Str::random(5)),
                    'petugas_id' => $petugas->id,
                    'siswa_id' => $request->siswa_id,
                    'nisn' => $request->nisn,
                    'tanggal_bayar' => Carbon::now('Asia/Jakarta'),
                    'semester_bayar' => $bulan, // Menyimpan "Ganjil" atau "Genap"
                    'tahun_bayar' => $request->tahun_bayar,
                    'jumlah_bayar' => $request->jumlah_bayar,
                    'bukti_pembayaran' => isset($fileName) ? $fileName : null, // Menyimpan nama file bukti pembayaran ke database
                ]);
                Log::info('Data Pembayaran: ', [
                    'kode_pembayaran' => 'SPPR' . Str::upper(Str::random(5)),
                    'petugas_id' => $petugas->id,
                    'siswa_id' => $request->siswa_id,
                    'nisn' => $request->nisn,
                    'tanggal_bayar' => Carbon::now('Asia/Jakarta'),
                    'semester_bayar' => $bulan, // "Ganjil" atau "Genap"
                    'tahun_bayar' => $request->tahun_bayar,
                    'jumlah_bayar' => $request->jumlah_bayar,
                    'bukti_pembayaran' => $fileName, // Nama file bukti pembayaran
                ]);
            }

            // Kirim notifikasi email setelah pembayaran berhasil
            $data = [
                'nama_siswa' => $siswa->nama_siswa,
                'nisn' => $siswa->nisn,
                'nis' => $siswa->nis,
                'jumlah_bayar' => $request->jumlah_bayar,
                'semester_bayar' => implode(", ", $semester_bayar), // Ganjil/Genap
                'tahun_bayar' => $request->tahun_bayar,
                'kelas' => $siswa->kelas->nama_kelas,
                'petugas' => $petugas->nama_petugas,
                'kode_pembayaran' => 'SPPR' . Str::upper(Str::random(5)),
                'tanggal_bayar' => Carbon::now('Asia/Jakarta')->toDateString(),
                'bukti_pembayaran' => $fileName,
            ];

            // Generate PDF dari view notifpembayaran
            $pdf = PDF::loadView('emails.notifpembayaran', $data);

            // Kirim email dengan lampiran PDF
            Mail::send([], [], function ($message) use ($pdf, $siswa) {
                $message->to($siswa->email)
                    ->subject('Konfirmasi Pembayaran SPP')
                    ->attachData($pdf->output(), "Bukti Pembayaran.pdf")
                    ->setBody('Pembayaran SPP Anda telah berhasil diproses, berikut adalah bukti pembayaran Anda.');
            });
        });

        return redirect()->route('pembayaran.history-pembayaran')
            ->with('success', 'Pembayaran berhasil disimpan dan email notifikasi telah dikirim!');
    } else {
        return back()
            ->with('error', 'Siswa Dengan Nama: ' . $request->nama_siswa . ', NISN: ' . 
                $request->nisn . ' Sudah Membayar SPP di bulan yang diinput (' . 
                implode(", ", $pembayaran) . ') , di Tahun: ' . $request->tahun_bayar . '. Pembayaran Dibatalkan.');
    }
}



    
    
    public function statusPembayaran(Request $request)
    {
        if ($request->ajax()) {
            $data = Siswa::with(['kelas'])
                ->latest()
                ->get();
                
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btn = '<div class="row"><a href="'.route('pembayaran.status-pembayaran.show',$row->nisn).
                    '"class="btn btn-primary btn-sm">DETAIL</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pembayaran.status-pembayaran');
    }

    public function statusPembayaranShow(Siswa $siswa)
    {
        $spp = Spp::all();
        return view('pembayaran.status-pembayaran-tahun', compact('siswa', 'spp'));
    }

    public function statusPembayaranShowStatus($nisn, $tahun)
    {
        // Mendapatkan data siswa berdasarkan NISN
        $siswa = Siswa::where('nisn', $nisn)->first();
        
        // Mendapatkan data SPP berdasarkan tahun
        $spp = Spp::where('tahun', $tahun)->first();
    
        // Mengambil semua data pembayaran untuk siswa dan tahun yang relevan
        $pembayaran = Pembayaran::with(['siswa'])
            ->where('siswa_id', $siswa->id)
            ->where('tahun_bayar', $spp->tahun)
            ->get();
    
        // Mengirim data siswa, spp, dan pembayaran (termasuk bukti_pembayaran) ke view
        return view('pembayaran.status-pembayaran-show', compact('siswa', 'spp', 'pembayaran'));
    }
    

    public function historyPembayaran(Request $request)
    {
        if ($request->ajax()) {
            $data = Pembayaran::with(['petugas', 'siswa' => function($query){
                $query->with('kelas');
            }])
                ->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btn = '<div class="row"><a href="'.route('pembayaran.history-pembayaran.print',$row->id).'"class="btn btn-danger btn-sm ml-2" target="_blank">
                    <i class="fas fa-print fa-fw"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

    	return view('pembayaran.history-pembayaran');
    }

    public function printHistoryPembayaran($id)
    {
        $data['pembayaran'] = Pembayaran::with(['petugas', 'siswa'])
            ->where('id', $id)
            ->first();

        $pdf = PDF::loadView('pembayaran.history-pembayaran-preview',$data);
        return $pdf->stream();
    }

    public function laporan()
    {
        return view('pembayaran.laporan');
    }

    public function printPdf(Request $request)
    {
        $tanggal = $request->validate([
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
        ]);

        $data['pembayaran'] = Pembayaran::with(['petugas', 'siswa'])
            ->whereBetween('tanggal_bayar', $tanggal)->get();

        if ($data['pembayaran']->count() > 0) {
            $pdf = PDF::loadView('pembayaran.laporan-preview', $data);
            return $pdf->download('pembayaran-spp-'.
            Carbon::parse($request->tanggal_mulai)->format('d-m-Y').'-'.
            Carbon::parse($request->tanggal_selesai)->format('d-m-Y').
            Str::random(9).'.pdf');   
        }else{
            return back()->with('error', 'Data pembayaran spp tanggal '.
                Carbon::parse($request->tanggal_mulai)->format('d-m-Y').' sampai dengan '.
                Carbon::parse($request->tanggal_selesai)->format('d-m-Y').' Tidak Tersedia');
        }
    }
    private function convertPdfToImage($pdfPath)
{
    $outputDir = storage_path('app/public/bukti_pembayaran/images');
    if (!File::exists($outputDir)) {
        File::makeDirectory($outputDir, 0755, true);
    }

    $outputFile = $outputDir . '/image.jpg'; // Output file gambar

    // Konversi PDF ke gambar menggunakan Ghostscript
    $command = "gs -dNOPAUSE -dBATCH -sDEVICE=jpeg -r150 -sOutputFile={$outputFile} {$pdfPath}";
    exec($command);
}
}
