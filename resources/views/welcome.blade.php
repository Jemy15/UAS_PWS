<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rental Mobil</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite([])
</head>
<body class="bg-light">

<div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="card shadow-lg p-5 text-center" style="max-width: 500px;">
        
        <h2 class="mb-3 fw-bold">🚗 Sistem Rental Mobil</h2>
        <p class="text-muted mb-4">
            Aplikasi manajemen penyewaan mobil berbasis web  
            menggunakan Laravel REST API
        </p>

        @guest
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100 mb-2">Login</a>
            <a href="{{ route('register') }}" class="btn btn-outline-secondary w-100 mb-3">Register</a>
        @endguest

        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-success btn-lg w-100 mb-3">Dashboard</a>
        @endauth

        <small class="text-muted">
            © 2025 Rental Mobil | UAS Pemrograman Web Service
        </small>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
