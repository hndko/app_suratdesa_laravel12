# SIMADES - Sistem Informasi Manajemen Desa

SIMADES adalah aplikasi manajemen administrasi desa berbasis web untuk membantu pelayanan surat, data penduduk, pengaduan warga, publikasi informasi desa, laporan, dan pengaturan role/permission.

Aplikasi ini dibangun dengan Laravel 12, Blade, AdminLTE 3, Bootstrap, Spatie Permission, Spatie Activitylog, Maatwebsite Excel, DomPDF, queue Laravel, dan integrasi WhatsApp Fonnte.

## Fitur Utama

- Dashboard administrasi desa.
- Manajemen penduduk.
- Manajemen jenis surat dan template surat.
- Pengajuan surat online dari warga.
- Arsip dan status surat.
- Pengaduan warga dengan tracking kode tiket.
- Informasi dan pengumuman desa.
- Setting identitas aplikasi/desa.
- Export laporan Excel/PDF.
- RBAC berbasis role dan permission granular.
- Notifikasi WhatsApp melalui queue.

## Role Default

- `super-admin`
- `kades`
- `operator`
- Warga publik tanpa login untuk layanan pengajuan dan pengaduan.

## Kebutuhan

- PHP 8.2+
- Composer 2
- Node.js LTS dan npm
- MySQL/MariaDB
- Web server Apache/Nginx untuk hosting

## Instalasi Lokal Singkat

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan simades:sync-permissions
php artisan storage:link
npm run build
php artisan serve
```

Panduan lengkap: [docs/development.md](docs/development.md).

## Deployment

Panduan deployment tersedia untuk:

- Shared hosting
- VPS dengan Nginx/Apache, PHP-FPM, dan Supervisor
- Update production
- Queue worker
- Rollback ringkas

Baca: [docs/deployment.md](docs/deployment.md).

## Command Penting

Sinkronkan permission SIMADES setelah perubahan RBAC atau deployment:

```bash
php artisan simades:sync-permissions
```

Daftar command operasional: [docs/commands.md](docs/commands.md).

## Dokumentasi Project

- [AGENTS.md](AGENTS.md): aturan kerja, coding standard, RBAC, versioning, dan git workflow.
- [docs/development.md](docs/development.md): setup development lokal.
- [docs/deployment.md](docs/deployment.md): deployment shared hosting dan VPS.
- [docs/commands.md](docs/commands.md): daftar command maintenance.
- [docs/prd.md](docs/prd.md): dokumen kebutuhan produk.

## Versioning

Versi aplikasi mengikuti SemVer dan dibaca dari `APP_VERSION`.

```env
APP_VERSION=v1.1.0
```

Jika `.env` lokal atau production masih memakai versi lama, update manual lalu jalankan:

```bash
php artisan config:clear
```

## Maintenance Production

Urutan umum setelah pull update:

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan simades:sync-permissions
php artisan optimize
php artisan queue:restart
```

Backup database sebelum migration atau perubahan RBAC besar.

## Lisensi

Project ini mengikuti lisensi yang tercantum di repository.
