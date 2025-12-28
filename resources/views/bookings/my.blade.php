<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Booking Saya | Rental Mobil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<div class="container p-4">
    <h3 class="mb-4">Booking Saya</h3>

    <a href="{{ url('/cars') }}" class="btn btn-secondary mb-3">Kembali ke Daftar Mobil</a>

    <div id="alertContainer"></div>

    <div id="bookingList" class="row">
        <div class="col-12 text-center text-muted">Loading daftar booking...</div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const token = localStorage.getItem('token');
const bookingList = document.getElementById('bookingList');
const alertContainer = document.getElementById('alertContainer');

// Fungsi menampilkan alert
function showAlert(message, type = 'success', timeout = 5000) {
    const alertId = 'alert-' + Date.now();
    const alert = document.createElement('div');
    alert.id = alertId;
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.role = 'alert';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    alertContainer.appendChild(alert);

    if(timeout > 0) {
        setTimeout(() => {
            const el = document.getElementById(alertId);
            if(el) {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
                bsAlert.close();
            }
        }, timeout);
    }
}

function fetchBookings() {
    bookingList.innerHTML = 'Loading...';

    fetch('{{ url("/api/bookings/my") }}', {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('HTTP error ' + res.status);
        return res.json();
    })
    .then(data => {
        if (!data || !data.data || data.data.length === 0) {
            bookingList.innerHTML = '<div class="col-12 text-center text-muted">Anda belum memiliki booking.</div>';
            return;
        }

        bookingList.innerHTML = '';
        data.data.forEach(booking => {
            const card = document.createElement('div');
            card.className = 'col-md-6 mb-3';
            card.innerHTML = `
                <div class="card shadow-sm p-3">
                    <h5>${booking.car.make} ${booking.car.model}</h5>
                    <p><strong>Tanggal Sewa:</strong> ${booking.start_date} s/d ${booking.end_date}</p>
                    <p><strong>Status:</strong> ${booking.status}</p>
                    <p><strong>Total Harga:</strong> Rp ${(booking.total_price || 0).toLocaleString()}</p>
                </div>
            `;
            bookingList.appendChild(card);
        });
    })
    .catch(err => {
        bookingList.innerHTML = `<div class="col-12 text-center text-danger">Error: ${err}</div>`;
    });
}

fetchBookings();
</script>

</body>
</html>
