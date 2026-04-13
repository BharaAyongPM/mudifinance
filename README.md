<p align="center">
  <img src="public/images/logo/logo.png" alt="MudiFinance Logo" width="120">
</p>

# MudiFinance

MudiFinance adalah aplikasi keuangan multi-user berbasis Laravel untuk mencatat pemasukan, pengeluaran, akun kas, kategori, unit bisnis, laporan, dan cetak data dalam satu dashboard yang rapi.

Project ini dibangun dan dibranding untuk **Mustika Digital Nusantara**.

## Ringkasan

MudiFinance dirancang untuk kebutuhan operasional keuangan harian dengan alur yang sederhana tetapi tetap cocok dipakai oleh tim. Admin dapat mengelola user, sementara tiap user bekerja pada data keuangannya sendiri agar tidak tercampur dengan user lain.

Fokus utama aplikasi ini:

- Pencatatan transaksi pemasukan dan pengeluaran
- Manajemen akun kas, kategori, dan unit bisnis
- Dashboard ringkas untuk monitoring keuangan
- Laporan dan halaman cetak
- Login multi-user dengan pemisahan data per user
- Branding logo, favicon, dan halaman login yang sudah disesuaikan

## Fitur Utama

- **Dashboard keuangan**
  Menampilkan ringkasan cashflow, statistik transaksi, dan indikator penting lain.

- **Transaksi**
  CRUD transaksi dengan relasi ke akun kas, kategori, dan unit bisnis.

- **Master data**
  Mengelola:
  - akun kas
  - kategori pemasukan/pengeluaran
  - unit bisnis

- **Kategori yang lebih visual**
  Kategori menggunakan:
  - color picker
  - pilihan icon finance yang umum
  - preview warna dan icon

- **Laporan**
  Menyediakan tampilan laporan dan halaman print untuk kebutuhan operasional maupun arsip.

- **Role user**
  - `admin`: mengelola user dan data aplikasi
  - `user`: mengakses data keuangannya sendiri

## Teknologi

- Laravel 12
- PHP 8.2+
- MySQL 8
- Blade
- Tailwind CSS 4
- Alpine.js
- Docker + Docker Compose

## Struktur Fitur

Beberapa route utama di aplikasi ini:

- `/login`
- `/dashboard`
- `/transactions`
- `/accounts`
- `/categories`
- `/business-units`
- `/reports`
- `/reports/print`
- `/users` khusus admin

## Persyaratan Lokal

Sebelum menjalankan project secara lokal, siapkan:

- PHP 8.2 atau lebih baru
- Composer
- Node.js dan npm
- MySQL

## Instalasi Lokal

1. Clone repository ini.
2. Install dependency PHP.

```bash
composer install
```

3. Install dependency front-end.

```bash
npm install
```

4. Copy file environment.

```bash
copy .env.example .env
```

5. Sesuaikan konfigurasi `.env`, terutama:

```env
APP_NAME=MudiFinance
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=keuangan
DB_USERNAME=root
DB_PASSWORD=
```

6. Generate application key.

```bash
php artisan key:generate
```

7. Buat symbolic link storage bila diperlukan.

```bash
php artisan storage:link
```

8. Pilih salah satu metode database berikut:

### Opsi A: Pakai dump database yang sudah ada

Jika Anda sudah memiliki file SQL, import dulu database lalu **jangan jalankan migrasi awal**.

Contoh:

```bash
mysql -u root -p keuangan < public/keuangan.sql
```

### Opsi B: Database baru dari nol

Jika belum ada dump database, jalankan migrasi lalu seed default data:

```bash
php artisan migrate
php artisan db:seed --class=FinanceSeeder
```

Seeder `FinanceSeeder` akan menyiapkan akun admin default, akun kas awal, dan kategori awal.

9. Jalankan asset front-end.

```bash
npm run dev
```

10. Jalankan aplikasi Laravel.

```bash
php artisan serve
```

## Default Admin Seeder

Jika Anda memakai `FinanceSeeder`, akun admin default berasal dari konfigurasi `config/finance.php`:

- Nama: `Admin MudiFinance`
- Email: `admin@mudifinance.app`
- Password: `admin12345`

Sebaiknya ubah kredensial ini setelah pertama kali login.

## Deployment Dengan Docker

Repository ini sudah menyertakan file Docker produksi:

- [Dockerfile](Dockerfile)
- [docker-compose.yml](docker-compose.yml)
- [docker/entrypoint.sh](docker/entrypoint.sh)

### Perilaku Docker

- Service `app` menjalankan Laravel di Apache
- Service `db` menjalankan MySQL 8
- Port aplikasi mengikuti `APP_PORT`
- File `public/keuangan.sql` akan diimport otomatis oleh MySQL **pada inisialisasi volume database pertama**

### Variabel Environment Penting Untuk Docker

Pastikan `.env` di server menyesuaikan nilai seperti berikut:

```env
APP_NAME=MudiFinance
APP_ENV=production
APP_DEBUG=false
APP_URL=http://your-server:8083
APP_PORT=8083

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=keuangan
DB_USERNAME=mudifinance
DB_PASSWORD=your_password
DB_ROOT_PASSWORD=your_root_password
```

### Menjalankan Docker

```bash
docker compose up -d --build
```

### Catatan Penting Soal Import SQL

Jika memakai `public/keuangan.sql`:

- jangan jalankan migrasi awal
- pastikan file SQL berformat teks biasa, bukan UTF-16
- import otomatis hanya terjadi saat volume MySQL masih baru

Jika Anda ingin mengulang import dari dump SQL yang sama:

```bash
docker compose down -v
docker compose up -d --build
```

Perintah di atas akan menghapus volume database container MudiFinance dan menginisialisasi ulang dari `public/keuangan.sql`.

## Branding

Branding aplikasi dikendalikan dari `config/finance.php`, termasuk:

- `app_name`
- `company_name`
- `copyright_name`
- `logo_path`

Logo utama saat ini menggunakan:

```php
'logo_path' => 'images/logo/logo.png'
```

Favicon dan halaman login juga sudah memakai logo yang sama agar branding konsisten.

## Lokasi Penting Project

- `app/Http/Controllers` untuk controller utama
- `resources/views` untuk Blade views
- `config/finance.php` untuk identitas aplikasi dan default data
- `public/images/logo` untuk aset logo
- `public/keuangan.sql` untuk dump database existing

## Catatan Pengembangan

- Aplikasi ini memakai pemisahan data berbasis `user_id`
- Kategori default diprovisikan otomatis untuk user baru
- Halaman login, sidebar, dan report print sudah terhubung ke branding logo
- README ini aman untuk publik karena tidak memuat kredensial server/deploy

## Tentang Mustika Digital Nusantara

MudiFinance dikembangkan sebagai bagian dari kebutuhan branding dan operasional **Mustika Digital Nusantara**. Jika repository ini dipublikasikan ke GitHub, bagian ini membantu menjelaskan identitas project dan kepemilikan brand sejak awal.

## Lisensi

Silakan sesuaikan lisensi repository ini dengan kebijakan distribusi project Anda sebelum dipublikasikan secara publik.
