<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rental Mobil</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="card shadow-lg p-5 text-center" style="max-width: 500px;">
            
            <h2 class="mb-3 fw-bold">🚗 Sistem Rental Mobil</h2>
            <p class="text-muted mb-4">
                Aplikasi manajemen penyewaan mobil berbasis web  
                menggunakan Laravel REST API
            </p>

            <!-- Tombol Login -->
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100 mb-2">
                Login
            </a>

            <!-- Tombol Register -->
            <a href="{{ route('register') }}" class="btn btn-outline-secondary w-100 mb-3">
                Register
            </a>

            <small class="text-muted">
                © 2025 Rental Mobil | UAS Pemrograman Web Service
            </small>
        </div>
    </div>

</body>
</html>
