<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPP | Email Pemberitahuan</title>
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
                <h4 class="text-center mb-4">Email Pemberitahuan</h4>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Form Lupa Password -->
                <form action="{{ route('emailpemberitahuan.email') }}" method="POST" novalidate onsubmit="return validateForm()">
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

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-group">
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kendala -->
                    <div class="form-group">
                        <label for="kendala">Topik</label>
                        <select name="kendala" id="kendala" class="form-control" required onchange="showPemberitahuan()">
                            <option value="" disabled selected>Pilih Topik</option>
                            <option value="Jatuh Tempo Pembayaran">Jatuh Tempo Pembayaran</option>
                            <option value="Bukti Pembayaran">Bukti Pembayaran</option>
                        </select>
                        @error('kendala')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Pilihan untuk Menggunakan Kalimat Default -->
                    <div class="form-group">
                        <label for="use_default">Gunakan Kalimat Default</label>
                        <input type="checkbox" id="use_default" name="use_default" value="1" onclick="toggleDefaultMessage()">
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
                    <button type="submit" class="btn btn-primary btn-block">Kirim Permintaan</button>

                    <!-- Link Kembali ke Login -->
                    <p class="mt-3 text-center">
                        <a href="{{ route('login') }}">Kembali </a>
                    </p>
                </form>

            </div>
        </div>
    </div>

    <script>
        function showPemberitahuan() {
            var kendala = document.getElementById("kendala").value;
            var pemberitahuanText = '';

            if (kendala === "Jatuh Tempo Pembayaran") {
                pemberitahuanText = 'Yth. [Nama Siswa/Orang Tua/Wali], Kami informasikan bahwa pembayaran SPP semesteran akan jatuh tempo pada tanggal [tanggal jatuh tempo]. Mohon segera melakukan pembayaran sebelum tanggal tersebut untuk menghindari denda atau kendala lainnya. Terima kasih atas perhatian dan kerjasamanya.';
            } else if (kendala === "Bukti Pembayaran") {
                pemberitahuanText = 'Yth. [Nama Siswa/Orang Tua/Wali], Kami telah menerima laporan pembayaran SPP Anda. Namun, kami menemukan bahwa bukti pembayaran belum dilampirkan atau kurang lengkap. Mohon segera mengunggah atau menyerahkan bukti pembayaran ke sistem atau bagian administrasi sekolah. Terima kasih atas perhatian dan kerjasamanya.';
            }

            // Tampilkan pemberitahuan berdasarkan topik
            document.getElementById("defaultMessage").style.display = 'none';
        }

        function toggleDefaultMessage() {
            var useDefault = document.getElementById("use_default").checked;
            var deskripsi = document.getElementById("deskripsi");

            // Cek jika checkbox dicentang dan jika topik sudah dipilih
            if (useDefault) {
                var kendala = document.getElementById("kendala").value;
                var defaultText = '';

                if (kendala === "Jatuh Tempo Pembayaran") {
                    defaultText = 'Yth. [Nama Siswa/Orang Tua/Wali], Kami informasikan bahwa pembayaran SPP semesteran akan jatuh tempo pada tanggal [tanggal jatuh tempo]. Mohon segera melakukan pembayaran sebelum tanggal tersebut untuk menghindari denda atau kendala lainnya. Terima kasih atas perhatian dan kerjasamanya.';
                } else if (kendala === "Bukti Pembayaran") {
                    defaultText = 'Yth. [Nama Siswa/Orang Tua/Wali], Kami telah menerima laporan pembayaran SPP Anda. Namun, kami menemukan bahwa bukti pembayaran belum dilampirkan atau kurang lengkap. Mohon segera mengunggah atau menyerahkan bukti pembayaran ke sistem atau bagian administrasi sekolah. Terima kasih atas perhatian dan kerjasamanya.';
                }

                // Sisipkan kalimat default ke dalam kolom deskripsi
                deskripsi.value = defaultText;

                // Menonaktifkan kolom deskripsi dan validasi required
                deskripsi.disabled = true;
                deskripsi.removeAttribute('required');
            } else {
                // Menghapus kalimat default dan membiarkan kolom deskripsi dapat diedit
                deskripsi.value = '';
                deskripsi.disabled = false;

                // Mengaktifkan kembali validasi required
                deskripsi.setAttribute('required', 'true');
            }
        }

        function validateForm() {
            var deskripsi = document.getElementById("deskripsi");

            // Cek apakah deskripsi tidak diisi dan tidak dinonaktifkan
            if (deskripsi.disabled || deskripsi.value.trim() !== "") {
                return true; // Validasi lolos
            }
            alert("Deskripsi harus diisi jika kalimat default tidak digunakan.");
            return false; // Tidak mengirim form
        }
    </script>
</body>
</html>
