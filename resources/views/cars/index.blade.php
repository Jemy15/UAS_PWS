<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Mobil | Rental Mobil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }

        /* Kotak mobil grid responsif */
        #carsList {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        .car-card {
            flex: 1 1 calc(50% - 12px); /* 2 kolom di layar normal */
            min-width: 220px;          /* minimal lebar kotak */
            max-width: 100%;
        }
        .car-card .card {
            padding: 10px;
            min-height: 180px;         /* kotak lebih pendek */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Tombol Sewa selalu di bawah */
        .car-card .btn {
            margin-top: 5px;
        }

        /* Kotak Response API */
        #apiResponse {
            background: #212529;
            color: #f8f9fa;
            padding: 8px;
            border-radius: 5px;
            font-size: 12px;
            overflow: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
            height: 400px;
        }

        .spinner-border.spinner-border-sm {
            margin-left: 6px;
        }
    </style>
</head>
<body>

<div class="container-fluid p-4">
    <h3 class="mb-4">Daftar Mobil</h3>

    <a id="myBookingBtn" href="{{ url('/bookings/my') }}" class="btn btn-success mb-3">
        Lihat Booking Saya
    </a>

    <div id="alertContainer"></div>

    <div class="row">
        <!-- KIRI: Daftar Mobil -->
        <div class="col-md-8">
            <div id="carsList">
                <div class="text-center text-muted w-100">Loading daftar mobil...</div>
            </div>
        </div>

        <!-- KANAN: Response API -->
        <div class="col-md-4">
            <h5 class="fw-bold mb-3">Response (Postman Style)</h5>
            <pre id="apiResponse">Klik tombol Sewa untuk melihat response API...</pre>
        </div>
    </div>
</div>

<!-- Modal Sewa Mobil -->
<div class="modal fade" id="bookModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="bookingForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Sewa Mobil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
          <input type="hidden" id="carIdInput" name="car_id">
          <div class="mb-3">
              <label class="form-label">Tanggal Mulai</label>
              <input type="date" class="form-control" id="startDate" name="start_date" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Tanggal Selesai</label>
              <input type="date" class="form-control" id="endDate" name="end_date" required>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary" id="submitBookingBtn">
          Sewa
          <span id="bookingSpinner" class="spinner-border spinner-border-sm d-none"></span>
        </button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const token = localStorage.getItem('token');
const carsList = document.getElementById('carsList');
const myBookingBtn = document.getElementById('myBookingBtn');
const alertContainer = document.getElementById('alertContainer');
const bookModal = new bootstrap.Modal(document.getElementById('bookModal'));
const bookingForm = document.getElementById('bookingForm');
const carIdInput = document.getElementById('carIdInput');
const submitBookingBtn = document.getElementById('submitBookingBtn');
const bookingSpinner = document.getElementById('bookingSpinner');
const apiResponse = document.getElementById('apiResponse');

function showAlert(msg, type='success', timeout=4000){
    const alertId='alert-'+Date.now();
    const alert=document.createElement('div');
    alert.id=alertId;
    alert.className=`alert alert-${type} alert-dismissible fade show`;
    alert.innerHTML=`${msg}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
    alertContainer.appendChild(alert);
    if(timeout>0) setTimeout(()=>{ const el=document.getElementById(alertId); if(el){ bootstrap.Alert.getOrCreateInstance(el).close(); }}, timeout);
}

function fetchCars(){
    carsList.innerHTML='<div class="w-100 text-center text-muted">Loading...</div>';
    fetch('{{ url("/api/cars") }}', { headers: { 'Authorization':'Bearer '+token } })
    .then(res=>{ if(!res.ok) throw new Error('HTTP '+res.status); return res.json(); })
    .then(data=>{
        apiResponse.textContent = JSON.stringify(data,null,2);
        if(!data.data || data.data.length===0){ carsList.innerHTML='<div class="w-100 text-center text-muted">Tidak ada mobil tersedia</div>'; return;}
        carsList.innerHTML='';
        data.data.forEach(car=>{
            const card=document.createElement('div');
            card.className='car-card';
            card.innerHTML=`
                <div class="card shadow-sm p-2 h-100">
                    <h6>${car.make} ${car.model}</h6>
                    <p class="mb-1">Tahun: ${car.year}</p>
                    <p class="mb-1">Stock: ${car.stock}</p>
                    <p class="mb-1">Rp ${car.price_per_day.toLocaleString()}/hari</p>
                    <button class="btn btn-primary w-100" onclick="openBookingModal(${car.id})" ${car.stock<=0?'disabled':''}>Sewa</button>
                </div>`;
            carsList.appendChild(card);
        });
    })
    .catch(err=>{
        carsList.innerHTML='<div class="w-100 text-center text-danger">Error: '+err+'</div>';
        apiResponse.textContent='Error: '+err;
    });
}

function openBookingModal(carId){ carIdInput.value=carId; bookingForm.reset(); bookModal.show(); }

bookingForm.addEventListener('submit', e=>{
    e.preventDefault();
    const start=bookingForm.start_date.value, end=bookingForm.end_date.value;
    if(!start||!end){ showAlert('Tanggal wajib diisi','warning'); return;}
    if(end<start){ showAlert('Tanggal selesai harus setelah mulai','warning'); return;}
    submitBookingBtn.disabled=true; bookingSpinner.classList.remove('d-none');

    fetch('{{ url("/api/bookings") }}',{
        method:'POST',
        headers:{'Authorization':'Bearer '+token,'Content-Type':'application/json','Accept':'application/json'},
        body: JSON.stringify({car_id:carIdInput.value,start_date:start,end_date:end})
    })
    .then(res=>{ submitBookingBtn.disabled=false; bookingSpinner.classList.add('d-none'); if(!res.ok) return res.json().then(errData=>Promise.reject(errData)); return res.json(); })
    .then(data=>{ apiResponse.textContent=JSON.stringify(data,null,2); showAlert('Sewa berhasil!','success'); fetchCars(); bookModal.hide(); })
    .catch(err=>{
        let msg='Terjadi kesalahan';
        if(err.message) msg=err.message; else if(err.errors) msg=Object.values(err.errors).flat().join('<br>');
        apiResponse.textContent=JSON.stringify(err,null,2);
        showAlert(msg,'danger',6000);
    });
});

fetchCars();
</script>

</body>
</html>
