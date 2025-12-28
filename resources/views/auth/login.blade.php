<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Rental Mobil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid vh-100 p-4">
    <div class="row h-100">

        <!-- KIRI : FORM LOGIN -->
        <div class="col-md-5 d-flex align-items-center">
            <div class="card shadow-sm p-4 w-100">
                <h4 class="fw-bold mb-3">Login</h4>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" id="email" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" id="password" class="form-control">
                </div>

                <button id="loginBtn" class="btn btn-primary w-100" onclick="login()">
                    Login
                </button>

                <!-- Tombol lihat mobil setelah login sukses -->
                <a id="carsBtn" href="/cars" class="btn btn-success w-100 mt-2" style="display:none;">
                    Lihat Mobil
                </a>
            </div>
        </div>

        <!-- KANAN : RESPONSE API -->
        <div class="col-md-7 d-flex align-items-center">
            <div class="card shadow-sm p-4 w-100 h-100">
                <h5 class="fw-bold mb-3">Response (Postman Style)</h5>

                <pre id="response"
                     class="bg-dark text-light p-3 rounded h-100"
                     style="font-size: 13px; overflow:auto;">
Klik Login untuk melihat response API
                </pre>
            </div>
        </div>

    </div>
</div>

<script>
function login() {
    const responseBox = document.getElementById('response');
    const carsBtn = document.getElementById('carsBtn');
    responseBox.textContent = 'Loading...';

    fetch('/api/login', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'Accept': 'application/json' // penting supaya Laravel membaca JSON
        },
        body: JSON.stringify({
            email: document.getElementById('email').value,
            password: document.getElementById('password').value
        })
    })
    .then(res => res.json())
    .then(data => {
        // tampilkan RAW response seperti Postman
        responseBox.textContent = JSON.stringify(data, null, 2);

        // ambil token dari data.data?.token (sesuai AuthController)
        const token = data.data?.token;

        if (token) {
            localStorage.setItem('token', token);

            // tampilkan tombol Lihat Mobil setelah login sukses
            carsBtn.style.display = 'block';
        }
    })
    .catch(err => {
        responseBox.textContent = 'Error: ' + err;
    });
}
</script>

</body>
</html>
