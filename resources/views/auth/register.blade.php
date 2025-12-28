<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register | Rental Mobil</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid vh-100 p-4">
    <div class="row h-100">

        <!-- KIRI : FORM REGISTER -->
        <div class="col-md-5 d-flex align-items-center">
            <div class="card shadow-sm p-4 w-100">
                <h4 class="fw-bold mb-3">Register</h4>

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" id="name" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" id="email" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" id="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" class="form-control">
                </div>

                <button id="registerBtn" class="btn btn-primary w-100" onclick="register()">
                    Register
                </button>

                <!-- Tombol Login muncul setelah register sukses -->
                <a id="loginBtn" href="{{ route('login') }}" class="btn btn-success w-100 mt-2 d-none">
                    Login
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
Klik Register untuk melihat response API
                </pre>
            </div>
        </div>

    </div>
</div>

<script>
function register() {
    const responseBox = document.getElementById('response');
    const loginBtn = document.getElementById('loginBtn');
    responseBox.textContent = 'Loading...';

    fetch('/api/register', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
            password_confirmation: document.getElementById('password_confirmation').value
        })
    })
    .then(res => res.json())
    .then(data => {
        responseBox.textContent = JSON.stringify(data, null, 2);

        // ambil token dari data.token
        const token = data.data?.token;
        if (token) {
            localStorage.setItem('token', token);

            // tampilkan tombol Login setelah register sukses
            loginBtn.classList.remove('d-none');
        }
    })
    .catch(err => {
        responseBox.textContent = 'Error: ' + err;
    });
}
</script>

</body>
</html>
