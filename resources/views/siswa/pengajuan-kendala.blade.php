<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPP | Pengajuan Kendala</title>
    <link rel="stylesheet" href="{{ asset('templates/backend/AdminLTE-3.1.0') }}/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .login-box {
            margin: 5% auto;
        }
        .text-danger {
            font-size: 0.875rem;
        }
        .btn-block {
            margin-top: 15px;
        }
        #defaultMessage {
            display: none;
            margin-top: 20px;
            font-size: 1rem;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f1f1f1;
        }
    </style>
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card">
            <div class="card-body login-card-body">
                <h4 class="text-center mb-4">Pengajuan Kendala</h4>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Form Pengajuan Kendala-->
                <form action="{{ route('pengajuankendala.kirimPengajuan') }}" method="POST" novalidate onsubmit="return validateForm()">
    @csrf

    <!-- Nama Lengkap -->
    <div class="form-group">
        <label for="nama_lengkap">Nama Lengkap</label>
        <div class="input-group">
            <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" placeholder="Nama Lengkap" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
        </div>
        @error('nama_lengkap')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Email Petugas / Sekolah -->
    <div class="form-group">
        <label for="email_sekolah">Email Petugas / Sekolah</label>
        <div class="input-group">
            <input type="email" name="email_sekolah" id="email_sekolah" class="form-control" placeholder="Email Petugas / Sekolah" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
        </div>
        @error('email_sekolah')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Email Pengguna -->
    <div class="form-group">
        <label for="email_pengguna">Email Pengguna</label>
        <div class="input-group">
            <input type="email" name="email_pengguna" id="email_pengguna" class="form-control" placeholder="Email Pengguna" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
        </div>
        @error('email_pengguna')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Kendala -->
    <div class="form-group">
        <label for="kendala">Kendala</label>
        <select name="kendala" id="kendala" class="form-control" required onchange="showPemberitahuan()">
            <option value="" disabled selected>Pilih Topik</option>
            <option value="Kendala Login">Kendala Login</option>
            <option value="Kendala Pembayaran">Kendala Pembayaran</option>
            <option value="Kesalahan Data">Kesalahan Data</option>
            <option value="Kendala Lainnya">Kendala Lainnya</option>
        </select>
        @error('kendala')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Deskripsi -->
    <div class="form-group">
        <label for="deskripsi">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" placeholder="Deskripsi kendala Anda"></textarea>
        @error('deskripsi')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Tombol Submit -->
    <button type="submit" class="btn btn-primary btn-block">Kirim</button>

    <!-- Link Kembali ke Login -->
    <p class="mt-3 text-center">
        <a href="{{ route('login') }}">Kembali</a>
    </p>
</form>



            </div>
        </div>
    </div>

    <script>
    // Fungsi untuk menangani perubahan pilihan kendala
    function showPemberitahuan() {
        var kendala = document.getElementById("kendala").value;
        
        // Menampilkan pemberitahuan sesuai dengan kendala yang dipilih
        if (kendala === "Kendala Pembayaran") {
            alert("Anda memilih Kendala Pembayaran. Pastikan untuk memberikan detail yang jelas.");
        } else if (kendala === "Kendala Login") {
            alert("Anda memilih Kendala Login. Jika masalah login berlanjut, coba reset password.");
        } else if (kendala === "Kesalahan Data") {
            alert("Anda memilih Kesalahan Data. Mohon periksa kembali data yang Anda masukkan.");
        } else if (kendala === "Kendala Lainnya") {
            alert("Anda memilih Kendala Lainnya. Mohon beri penjelasan lebih lanjut pada kolom deskripsi.");
        }
    }
</script>

</body>
</html>
