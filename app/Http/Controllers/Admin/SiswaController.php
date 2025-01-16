<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Spp;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class SiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:read-siswa'])->only(['index', 'show']);
        $this->middleware(['permission:create-siswa'])->only(['create', 'store']);
        $this->middleware(['permission:update-siswa'])->only(['edit', 'update']);
        $this->middleware(['permission:delete-siswa'])->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()->of(Siswa::with(['kelas', 'spp'])->get())
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-primary btn-sm btn-edit" id="'.$row->id.'">Edit</button>
                            <button class="btn btn-danger btn-sm btn-delete" id="'.$row->id.'">Delete</button>';
                })
                ->rawColumns(['action']) // Untuk memastikan HTML di kolom 'action' tidak di-escape
                ->make(true);
        }
    
        $spp = Spp::all();
        $kelas = Kelas::all();
    
        return view('admin.siswa.index', compact('spp', 'kelas'));
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_siswa' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username',
            'nisn' => 'required|string|max:20|unique:siswa,nisn',
            'nis' => 'required|string|max:20|unique:siswa,nis',
            'email' => 'required|email|max:255|unique:users,email',
            'alamat' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }

        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'username' => Str::lower($request->username),
                    'email' => $request->email,
                    'password' => Hash::make('sppr2021'),
                ]);

                $user->assignRole('siswa');

                Siswa::create([
                    'user_id' => $user->id,
                    'kode_siswa' => 'SSWR' . Str::upper(Str::random(5)),
                    'nisn' => $request->nisn,
                    'nis' => $request->nis,
                    'nama_siswa' => $request->nama_siswa,
                    'email' => $request->email,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'alamat' => $request->alamat,
                    'no_telepon' => $request->no_telepon,
                    'kelas_id' => $request->kelas_id,
                ]);
            });

            return response()->json(['message' => 'Data berhasil disimpan!']);
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan data siswa: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan, data tidak disimpan!'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $siswa = Siswa::with(['kelas', 'spp'])->findOrFail($id);
        return response()->json(['data' => $siswa]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_siswa' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:siswa,email,' . $id,
            'alamat' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 422);
        }

        try {
            $siswa = Siswa::findOrFail($id);

            $siswa->update([
                'nama_siswa' => $request->nama_siswa,
                'email' => $request->email,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'kelas_id' => $request->kelas_id,
            ]);

            return response()->json(['message' => 'Data berhasil diupdate!']);
        } catch (\Exception $e) {
            Log::error('Error saat mengupdate data siswa: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan, data tidak diupdate!'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $siswa = Siswa::findOrFail($id);
                $user = User::findOrFail($siswa->user_id);

                $user->delete();
                $siswa->delete();
            });

            return response()->json(['message' => 'Data berhasil dihapus!']);
        } catch (\Exception $e) {
            Log::error('Error saat menghapus data siswa: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan, data tidak dihapus!'], 500);
        }
    }
}
