# 🏛️ SIMADES

**Sistem Informasi Manajemen Desa berbasis Laravel untuk digitalisasi layanan administrasi desa.**

SIMADES adalah aplikasi web yang dirancang untuk membantu pemerintah desa mengelola data penduduk, kartu keluarga, layanan surat, pengaduan warga, publikasi informasi, laporan, serta kontrol akses pengguna dalam satu sistem yang terpusat.

Project ini dibuat sebagai solusi administrasi desa yang praktis untuk kebutuhan operasional harian: warga dapat mengajukan layanan secara online, operator dapat memproses data dan surat, kepala desa dapat memantau laporan, dan admin dapat mengatur role serta permission secara granular.

## ✨ Short Description GitHub

Sistem Informasi Manajemen Desa berbasis Laravel untuk layanan surat, penduduk, pengaduan, laporan, RBAC, dan tracking layanan publik.

## 🎯 Tujuan Project

- Mendigitalisasi proses administrasi desa yang sebelumnya manual.
- Mempermudah warga mengajukan surat dan pengaduan dari portal publik.
- Membantu operator desa mengelola data penduduk, KK, arsip surat, dan pengumuman.
- Memberi kepala desa akses monitoring terhadap surat, pengaduan, dan laporan.
- Menjaga akses fitur menggunakan role dan permission yang terstruktur.

## 🚀 Fitur Utama

- Dashboard statistik administrasi desa.
- Manajemen Kartu Keluarga.
- Manajemen data penduduk.
- Manajemen jenis surat dan template surat.
- Pengajuan surat online dari warga.
- Tracking status surat publik menggunakan kode tracking.
- Pengaduan warga dengan kode tiket.
- Pengumuman dan informasi desa.
- Export laporan Excel dan PDF.
- Activity log untuk audit aktivitas sistem.
- AI Adapter Gateway untuk OpenAI, OpenRouter, DeepSeek, Gemini, Claude, dan custom base URL.
- AI pengaduan, AI template surat, dan assistant internal backend.
- Import Excel data penduduk dan Kartu Keluarga dengan preview validasi.
- Approval surat bertingkat dan QR verifikasi surat publik.
- Manajemen user, role, dan permission granular.
- Pengaturan identitas desa dan aplikasi.
- Integrasi notifikasi WhatsApp melalui Fonnte dan queue Laravel.

## 👥 Role Pengguna

| Role | Fungsi |
| --- | --- |
| `super-admin` | Mengelola seluruh modul, user, role, permission, setting, dan data master. |
| `kades` | Melihat dashboard, memantau surat/pengaduan, mengakses laporan, dan melakukan monitoring. |
| `operator` | Mengelola data teknis seperti penduduk, KK, surat, pengaduan, pengumuman, dan setting tertentu. |
| Warga publik | Mengajukan surat, mengirim pengaduan, dan melacak status layanan tanpa login. |

## 🧰 Tech Stack

| Area | Teknologi |
| --- | --- |
| Backend | PHP 8.2+, Laravel 12 |
| Frontend backend | Blade, AdminLTE 3, Bootstrap 4 |
| Frontend publik | Blade, Sandbox Bootstrap 5 |
| Database | MySQL/MariaDB |
| Auth & RBAC | Spatie Laravel Permission |
| Audit log | Spatie Activitylog |
| Export | Maatwebsite Excel, DomPDF |
| Queue | Laravel Queue |
| Integrasi | Fonnte WhatsApp API, AI provider gateway |

## 🔎 Highlight Implementasi

- Permission dibuat granular sampai level aksi seperti `create`, `store`, `edit`, `update`, `destroy`, `preview`, `export`, dan `send`.
- Nomor surat menggunakan service counter agar lebih aman dari duplikasi dibanding pola `count()+1`.
- Form publik memakai validasi server-side dan CSRF.
- Flash message dan konfirmasi aksi memakai SweetAlert2/toast.
- Upload file memakai storage publik Laravel dan validasi file.
- Export dan laporan dipisahkan dalam class export agar lebih mudah dirawat.
- API key AI disimpan terenkripsi dan semua request AI melewati gateway service dengan timeout, retry, dan log.
- QR verifikasi surat publik membatasi data yang tampil agar tidak membuka NIK/alamat lengkap.
- Dokumentasi development, deployment, command, dan PRD tersedia di folder `docs/`.

## ⚙️ Instalasi Lokal Singkat

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan simades:sync-permissions
php artisan storage:link
php artisan serve
```

Panduan lengkap tersedia di [docs/02. development.md](docs/02.%20development.md).

## 🚢 Deployment

Dokumentasi deployment mencakup shared hosting, VPS, queue worker, update production, dan rollback ringkas.

Baca panduan di [docs/03. deployment.md](docs/03.%20deployment.md).

## 🧭 Command Penting

Sinkronkan permission setelah deployment, perubahan role, atau update fitur:

```bash
php artisan simades:sync-permissions
```

Daftar command operasional tersedia di [docs/04. commands.md](docs/04.%20commands.md).

## 📚 Dokumentasi

- [AGENTS.md](AGENTS.md): aturan kerja, coding standard, RBAC, versioning, dan git workflow.
- [docs/01. prd.md](docs/01.%20prd.md): dokumen kebutuhan produk.
- [docs/02. development.md](docs/02.%20development.md): setup development lokal.
- [docs/03. deployment.md](docs/03.%20deployment.md): deployment shared hosting dan VPS.
- [docs/04. commands.md](docs/04.%20commands.md): daftar command maintenance.

## 🏷️ Versioning

Versi aplikasi mengikuti SemVer dan dibaca dari `APP_VERSION`.

```env
APP_VERSION=v3.0.41
```

Jika `.env` lokal atau production masih memakai versi lama, update manual lalu jalankan:

```bash
php artisan config:clear
```

## 🛠️ Maintenance Production

Urutan umum setelah pull update:

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan simades:sync-permissions
php artisan optimize
php artisan queue:restart
```

Selalu backup database sebelum menjalankan migration atau perubahan RBAC besar di production.

## 📌 Status Project

Project berada pada versi `v3.0.41` dan ditujukan sebagai MVP production yang sudah dilengkapi AI Gateway, import penduduk, approval surat, QR verifikasi, notifikasi queue, halaman login staff, sidebar backend, dashboard operasional, modul Kartu Keluarga, Data Penduduk, Import Penduduk & KK, Data Jenis Surat, Buat Surat Baru, Arsip Surat, Pengumuman Desa, Pengaduan Warga, Laporan & Rekapitulasi, AI Assistant Internal, Uji Coba WhatsApp Gateway, AI Provider Gateway, AI Usage Logs, Manajemen User, Manajemen Role & Permission, Pengaturan Website & Desa lengkap dengan logo/favicon/SEO, Activity Log dengan detail perubahan terbaca, Edit Profil dengan konteks role/KK, Beranda Publik modern, Pengajuan Surat Online publik, Lacak Pengajuan Surat publik, serta Verifikasi Keaslian Surat publik yang lebih interaktif dan menjaga privasi. Uji manual tetap disarankan pada modul surat, pengaduan, export, permission role, AI provider, AI logs, user management, role management, setting website/desa, SEO, branding, activity log, edit profil, frontend beranda/pengajuan/lacak/verifikasi surat, dan integrasi WhatsApp sebelum digunakan pada data production sebenarnya.

## 📄 Lisensi

Project ini mengikuti lisensi yang tercantum di repository.
