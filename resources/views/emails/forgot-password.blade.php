<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Lupa Password</title>
</head>
<body>
    <h2>Permintaan Lupa Password</h2>
    <p>Halo {{ $username }},</p>  <!-- Menampilkan username -->
    <p>Anda telah mengajukan permintaan untuk mereset password Anda.</p>
    <p>Berikut adalah informasi akun Anda:</p>
    <ul>
        <li>Username: {{ $username }}</li>  <!-- Menambahkan username di informasi -->
        <li>Email: {{ $email }}</li>
        <li>Password Lama: {{ $oldPassword }}</li>
        <li>Password Baru: {{ $newPassword }}</li>
    </ul>
    <p>Silakan login menggunakan password baru Anda.</p>
    
    <!-- Link untuk login kembali -->
    <p>Untuk login kembali, silakan klik tombol berikut:</p>
    <p>
        <a href="{{ route('login') }}" style="padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">
            Login
        </a>
    </p>
</body>
</html>
