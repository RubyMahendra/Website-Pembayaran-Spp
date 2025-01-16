<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanKendala extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_kendala';
    
    protected $fillable = [
        'nama_lengkap',
        'email_sekolah',
        'email_pengguna',
        'kendala',
        'deskripsi',
        'status'
    ];
}

