<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ekonsul PKBI</title>
    <link rel="icon" type="image/png" href="https://pkbi-jatim.or.id/wp-content/uploads/2021/12/cropped-Logo-PKBI-Jatim.png">
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
        border-radius: 50%; /* Jadi lingkaran */
        width: 220px;
        height: 220px;
        margin: auto;
        padding: 20px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease-in-out;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: #fff;
        cursor: pointer;
      }

      .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.15);
      }

      .service-img {
        width: 100px;
        height: 100px;
        object-fit: contain;
        margin: 0 auto 10px;
      }

      .card-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 5px;
      }

      .discount-badge {
        font-size: 0.8rem;
        background: #d1e7dd;
        color: #e80000;
        border-radius: 5px;
        padding: 3px 6px;
        display: inline-block;
        margin-bottom: 5px;
      }

      .old-price {
        font-size: 0.85rem;
        color: #999;
        text-decoration: line-through;
      }

      .price-now {
        font-size: 1rem;
        font-weight: bold;
        color: #198754;
      }

      .hero-text {
        text-align: center;
        margin-bottom: 50px;
      }

      .hero-text h2 {
        font-weight: 700;
        color: #212529;
      }

      .hero-text p {
        font-size: 1.1rem;
        color: #555;
      }

      footer {
        margin-top: 60px;
        padding: 20px;
        text-align: center;
        color: #777;
      }

      a.card-link {
        text-decoration: none;
        color: inherit;
        display: block;
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
      <!-- Hero Text -->
      <div class="hero-text">
        <h2>Dapatkan Dukungan untuk Berbagai Kebutuhan</h2>
        <p>Seluruh prosedur medis dan tenaga kesehatan di Ekonsul dipastikan memenuhi standar regulasi dan etika layanan kesehatan tertinggi.</p>
      </div>

      {{-- <h3 class="mb-4 text-center fw-bold text-dark">Daftar Layanan Konseling</h3> --}}

      <div class="row justify-content-center">
        @foreach($layanans as $layanan)
         @php
          $img = match($layanan->nama_layanan) {
            'Konsultasi Kespro' => asset('public/iconberanda/reproduksi.png'),
            'Konsultasi Umum' => asset('public/iconberanda/umum.png'),
            'Konsultasi HIV IMS' => asset('public/iconberanda/hiv.png'),
            'Konsultasi Mental' => asset('public/iconberanda/mental.png'),
            'Konsultasi Gizi' => asset('public/iconberanda/gizi.png'),
            default => asset('public/iconberanda/umum.png'),
          };
        @endphp

          <div class="col-md-4 col-lg-3 mb-4 d-flex justify-content-center">
            <a href="{{ route('dashboard.keranjang', ['id' => $layanan->id_layanan]) }}" class="card-link">
              <div class="card">
                <img src="{{ $img }}" alt="{{ $layanan->nama_layanan }}" class="service-img">
                <h5 class="card-title">{{ $layanan->nama_layanan }}</h5>
                <span class="discount-badge">
                  Diskon {{ $layanan->jumlah_diskon_persen ?? 100 }}%
                </span>
                <div>
                  <span class="price-now old-price">Rp. {{ number_format($layanan->harga_layanan, 0, ',', '.') }}</span><br>
                  {{-- <span class="price-now">Rp. 0</span> --}}
                </div>
              </div>
            </a>
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
