<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ekonsul PKBI</title>
    <link rel="icon" type="image/png" href="{{ asset('https://pkbi-jatim.or.id/wp-content/uploads/2021/12/cropped-Logo-PKBI-Jatim.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      body {
        background: linear-gradient(to right, #e0f7fa, #fce4ec);
        font-family: 'Segoe UI', sans-serif;
      }

      .navbar {
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      }

      .card {
        border: none;
        border-radius: 15px;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        background-color: #ffffff;
      }

      .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      }

      .card-title {
        font-size: 1.2rem;
        border-radius: 8px;
        background-color: #eee;
        color: #000 !important;
      }

      .btn-primary {
        background-color: #0d6efd;
        border: none;
        transition: background-color 0.2s;
      }

      .btn-primary:hover {
        background-color: #0b5ed7;
      }

      .discount-badge {
        background-color: #d1e7dd;
        color: #0f5132;
        border-radius: 5px;
        padding: 4px 8px;
        font-size: 0.85rem;
        display: inline-block;
        margin-bottom: 8px;
      }

      .voucher-code {
        font-size: 1rem;
        color: #198754;
        font-weight: 600;
        background: #e9f7ef;
        padding: 6px 10px;
        border-radius: 6px;
        display: inline-block;
      }

      .old-price {
        color: #999;
        text-decoration: line-through;
      }

      footer {
        margin-top: 60px;
        padding: 20px;
        text-align: center;
        color: #777;
      }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg bg-primary navbar-dark">
      <div class="container">
        <a class="navbar-brand" href="#">
          <img src="https://pkbi-jatim.or.id/wp-content/uploads/2024/07/Untitled-design-1.png" alt="Logo" height="50">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <span class="navbar-text text-white fw-semibold fs-5">Ekonsul PKBI Jawa Timur</span>
            </li>
          </ul>
          <a href="{{ route('login') }}" class="btn btn-light">
            <i class="bi bi-door-open-fill"></i> Masuk / Daftar
          </a>
        </div>
      </div>
    </nav>

    <div class="container mt-5">
      <h2 class="mb-4 text-center fw-bold text-dark">Daftar Layanan Konseling</h2>

      <div class="row">
        @foreach($layanans as $index => $layanan)
          <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title px-3 py-2 mb-3" style="background-color: {{ $layanan->warna_layanan }};">
                  {{ $layanan->nama_layanan }}
                </h5>
                <span class="discount-badge">Potongan Diskon 100%</span>
                <p class="mb-1">Gunakan Kode Voucher:</p>
                <div class="voucher-code mb-2">PKBIJAYA</div>
                <div class="mb-2">
                  <span class="old-price">Rp. {{ number_format($layanan->harga_layanan, 0, ',', '.') }}</span>
                  <span class="ms-1 text-success fw-bold">Rp. 0</span>
                </div>
                <div class="mt-auto">
                  <a href="{{ route('dashboard.keranjang', ['id' => $layanan->id_layanan]) }}" class="btn btn-primary w-100">
                    <i class="bi bi-chat-heart-fill me-1"></i> Mulai Konseling
                  </a>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <footer>
      &copy; {{ date('Y') }} PKBI Jawa Timur. Seluruh hak dilindungi.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
