# Ekonsul PKBI - README

## Pendahuluan
Dokumen ini berisi langkah-langkah untuk menginstalasi dan menjalankan proyek **Ekonsul PKBI**.

## Prasyarat
Pastikan Anda telah menginstal:
- [PHP](https://www.php.net/downloads) versi terbaru
- [Composer](https://getcomposer.org/download/)
- [Node.js](https://nodejs.org/) dan npm
- Database MySQL atau MariaDB

## Langkah-langkah Instalasi

### 1. Clone Repository
Clone repository ini ke dalam direktori lokal Anda:
```bash
git clone https://github.com/username/ekonsulpkbi.git
cd ekonsulpkbi
```

### 2. Instalasi Dependensi PHP
Jalankan perintah berikut untuk menginstal dependensi PHP:
```bash
composer install
```

### 3. Instalasi Dependensi Frontend
Instal dependensi frontend menggunakan npm:
```bash
npm install
```

### 4. Konfigurasi File `.env`
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Edit file `.env` sesuai dengan konfigurasi database dan pengaturan lainnya.

### 5. Generate Application Key
Jalankan perintah berikut untuk menghasilkan application key:
```bash
php artisan key:generate
```

### 6. Migrasi dan Seed Database
Jalankan migrasi dan seed untuk mengatur struktur database:
```bash
php artisan migrate --seed
```

### 7. Build Frontend Assets
Kompilasi aset frontend:
```bash
npm run dev
```

### 8. Menjalankan Server Lokal
Jalankan server lokal menggunakan perintah:
```bash
composer run dev
```
Akses aplikasi melalui [http://localhost:8000](http://localhost:8000).

## Testing
Untuk menjalankan pengujian, gunakan perintah:
```bash
php artisan test
```

## Kontribusi
Silakan buat pull request jika ingin berkontribusi pada proyek ini.

## Lisensi
Proyek ini dilisensikan di bawah [MIT License](LICENSE).