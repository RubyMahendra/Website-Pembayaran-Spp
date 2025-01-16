<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
    </style>
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card">
            <div class="card-body login-card-body">
                <h4 class="text-center mb-4">Forgot Password</h4>

                <!-- Pesan Sukses -->
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Form Lupa Password -->
                <form action="{{ route('forgot-password.email') }}" method="POST" novalidate>
                    @csrf

                    
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

                    

                    <!-- Tombol Submit -->
                    <button type="submit" class="btn btn-primary btn-block">Kirim Permintaan</button>

                    <!-- Link Kembali ke Login -->
                    <p class="mt-3 text-center">
                        <a href="{{ route('login') }}">Kembali ke Login</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
