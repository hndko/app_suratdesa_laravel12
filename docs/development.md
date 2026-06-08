# Panduan Development Lokal SIMADES

Dokumen ini berisi langkah kerja lokal untuk menjalankan SIMADES di Laragon, XAMPP, Valet, Sail, atau PHP built-in server.

## Kebutuhan

- PHP 8.2 atau lebih baru.
- Composer 2.
- Node.js LTS dan npm.
- MySQL/MariaDB.
- Extension PHP umum Laravel: `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `zip`.

## Instalasi Awal

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Sesuaikan `.env` lokal:

```env
APP_NAME="SIMADES"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
APP_VERSION=v1.1.0

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_suratdesa_laravel12
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=database
FILESYSTEM_DISK=public
FONNTE_TOKEN=
DEFAULT_ADMIN_PASSWORD=password-yang-kuat
```

## Database

```bash
php artisan migrate --seed
php artisan simades:sync-permissions
```

Seeder akan membuat role default, permission, dan user awal sesuai `database/seeders/RolePermissionSeeder.php`.

## Storage

```bash
php artisan storage:link
```

Upload publik disimpan melalui disk `public`, sehingga symbolic link wajib tersedia.

## Menjalankan Aplikasi

Mode sederhana:

```bash
php artisan serve
npm run dev
```

Mode lengkap untuk queue dan log:

```bash
composer run dev
```

Jika tidak ingin memakai Vite dev server, build asset:

```bash
npm run build
```

## Queue Lokal

Notifikasi WhatsApp otomatis dikirim melalui queue. Jalankan worker saat menguji pengajuan surat atau pengaduan:

```bash
php artisan queue:work --tries=3 --timeout=60
```

## Reset Cache Saat Development

```bash
php artisan optimize:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear
php artisan permission:cache-reset
```

## Akun Default

Akun dibuat dari `RolePermissionSeeder.php`. Ganti `DEFAULT_ADMIN_PASSWORD` sebelum menjalankan seed di environment yang bukan lokal pribadi.

- `admin@example.com`
- `kades@example.com`
- `operator@example.com`

## Catatan

- Jangan commit `.env`.
- Jangan menjalankan `migrate:fresh --seed` pada database yang berisi data penting.
- Setelah menambah permission, route backend, tombol aksi, export, upload, atau test integrasi, update `RolePermissionSeeder.php`, lalu jalankan `php artisan simades:sync-permissions`.
