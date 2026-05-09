# SIMADES - Sistem Informasi Manajemen Desa Modern

[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

**SIMADES** (Sistem Informasi Desa) adalah platform manajemen administrasi desa berbasis web yang dirancang untuk mendigitalisasi layanan publik, mempermudah birokrasi, dan meningkatkan transparansi antara pemerintah desa dan warga. Dibangun dengan standar industri menggunakan **Laravel 12**, platform ini menawarkan pengalaman pengguna yang modern, cepat, dan handal.

---

## 🌟 Fitur Utama

### 🏢 Panel Administrasi (Backend)
- **Manajemen Kependudukan**: Kelola data warga dengan fitur ekspor Excel untuk pelaporan cepat.
- **Arsip & Pembuatan Surat**: Automasi pembuatan berbagai jenis surat desa (SKU, SKTM, dll) dengan template yang dapat disesuaikan.
- **Sistem Pengaduan Warga**: Dashboard khusus untuk memproses laporan warga secara efisien.
- **Audit Logs & Keamanan**: Pencatatan setiap aktivitas data (Activity Log) dan kontrol akses berbasis peran (RBAC) menggunakan Spatie.
- **Laporan & Rekapitulasi**: Penarikan laporan berkala dalam format Excel dan PDF yang siap cetak.

### 🌐 Portal Layanan Publik (Frontend)
- **Desain Premium**: Menggunakan template Sandbox v3.4.1 yang responsif dan estetik.
- **Pengajuan Surat Mandiri**: Warga dapat mengajukan surat secara online tanpa harus datang ke kantor desa.
- **Tracking Pengaduan**: Pantau status laporan secara real-time hanya dengan kode tiket.
- **Branding Dinamis**: Logo dan identitas desa dapat diubah langsung melalui panel pengaturan.

### 📲 Integrasi & Notifikasi
- **WhatsApp Gateway (Fonnte)**: Notifikasi otomatis ke warga saat surat selesai diproses atau pengaduan ditanggapi.
- **Clean Code Architecture**: Menggunakan Service-Pattern & Facades untuk pemeliharaan jangka panjang yang mudah.

---

## 🛠️ Teknologi yang Digunakan

| Komponen | Teknologi |
| --- | --- |
| **Framework** | Laravel 12 (Modern PHP) |
| **Database** | MySQL / SQLite |
| **UI/UX (Backend)** | AdminLTE 3 & Bootstrap 4 |
| **UI/UX (Frontend)** | Sandbox v3.4.1 (Modern Bootstrap 5) |
| **Integrasi** | Fonnte WhatsApp API |
| **Packages** | Spatie (Permission & ActivityLog), Maatwebsite Excel, DomPDF |

---

## 🚀 Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/username/app_suratdesa_laravel12.git
   ```

2. **Install Dependensi**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Setup Database**
   Sesuaikan konfigurasi DB di `.env`, lalu jalankan migrasi & seeder:
   ```bash
   php artisan migrate --seed
   ```

5. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```

---

## 📸 Showcase

> [!TIP]
> Desain frontend menggunakan konsep modern 2-column layout pada halaman autentikasi dan desain landing page yang premium untuk meningkatkan kepercayaan warga terhadap layanan digital desa.

---

## 📄 Lisensi

Proyek ini merupakan perangkat lunak sumber terbuka yang dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).

---

**Dikembangkan dengan ❤️ untuk kemajuan digitalisasi desa di Indonesia.**
