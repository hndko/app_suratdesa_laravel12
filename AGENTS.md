# AGENTS - Aturan Kerja dan Penulisan Project SIMADES

Dokumen ini adalah patokan wajib untuk semua agent/developer saat mengubah source code SIMADES. Jika ada konflik antara kebiasaan umum dan dokumen ini, ikuti `AGENTS.md`.

## 1. Konteks Project

- Nama aplikasi: SIMADES, Sistem Informasi Manajemen Desa.
- Stack utama: PHP 8.2+, Laravel 12, Blade, AdminLTE 3/Bootstrap 4 untuk backend, Sandbox Bootstrap 5 untuk frontend publik.
- Package utama: Spatie Permission, Spatie Activitylog, Maatwebsite Excel, DomPDF, Laravel queue, Fonnte WhatsApp API.
- Role utama: `super-admin`, `kades`, `operator`, dan warga publik tanpa login.
- Modul utama: dashboard, penduduk, jenis surat, surat, pengaduan, post/pengumuman, setting, report/export, user, role, profile, WhatsApp test.

## 2. Prinsip Umum

- Ikuti pola yang sudah ada di project. Jangan memindahkan struktur besar tanpa kebutuhan jelas.
- Jaga perubahan tetap terarah sesuai permintaan.
- Jangan menghapus perubahan user atau file lain yang tidak terkait.
- Untuk fitur baru, bugfix besar, PDF/export, upload file, scheduler, queue, transaksi data, atau integrasi eksternal, wajib cek:
  - Race condition dan concurrency
  - Performa dan UX bottleneck
  - Security: auth, permission, validation, upload, secret exposure, XSS, SQL injection, CSRF
  - Skalabilitas data
  - Optimasi database: N+1, indexing, query efficiency
  - Memory management
  - Timeout, retry, fallback external service
  - Error handling dan logging

## 3. Template, Layout, dan Struktur Folder

- Asset statis berada di `public/assets/` untuk CSS, JS, image, library, dan font.
- Controller aplikasi saat ini berada di `app/Http/Controllers/` tanpa subfolder `Backend` atau `Auth`. Jangan memindahkan controller ke subfolder baru kecuali ada refactor terencana yang menyeluruh.
- Model berada di `app/Models/`.
- Service berada di `app/Services/`.
- Queue job berada di `app/Jobs/`.
- Export Excel berada di `app/Exports/`.
- View backend berada di `resources/views/backend/[module]/`.
- View frontend publik berada di `resources/views/frontend/`.
- View pengajuan publik wajib dikelompokkan di `resources/views/frontend/pengajuan/[module]/`, contoh `pengajuan/surat/create.blade.php` dan `pengajuan/pengaduan/create.blade.php`.
- Layout berada di `resources/views/layouts/`.
- Partial topbar, sidebar, footer, atau komponen chrome layout tidak boleh dipisah. Semua komponen utama layout wajib tetap berada dalam satu file layout.
- Partial reusable non-layout boleh dibuat di `resources/views/partials/`, contoh partial global SweetAlert/toast.

### Layout Files

| Layout | File | Penggunaan |
| --- | --- | --- |
| Backend | `resources/views/layouts/app-backend.blade.php` | Halaman yang butuh sidebar, dashboard, CRUD, laporan, setting, profile, dan test integrasi |
| Auth | `resources/views/layouts/app-auth.blade.php` | Halaman authentication seperti login |
| Frontend publik | `resources/views/layouts/app-frontend-sandbox.blade.php` | Portal publik, pengajuan surat, pengaduan, dan tracking pengaduan |

### Struktur View

```text
resources/views/
├── layouts/
│   ├── app-backend.blade.php
│   ├── app-auth.blade.php
│   └── app-frontend-sandbox.blade.php
├── backend/
│   ├── dashboard.blade.php
│   └── [module]/
│       ├── index.blade.php
│       ├── create.blade.php
│       ├── edit.blade.php
│       └── show.blade.php
├── auth/
│   └── login.blade.php
└── frontend/
    ├── home.blade.php
    └── pengajuan/
        ├── surat/create.blade.php
        └── pengaduan/
            ├── create.blade.php
            └── track.blade.php
```

## 4. Layout dan Blade

- Layout backend: `resources/views/layouts/app-backend.blade.php`.
- Layout auth: `resources/views/layouts/app-auth.blade.php`.
- Layout frontend publik: `resources/views/layouts/app-frontend-sandbox.blade.php`.
- Layout hanya boleh menyediakan `@yield('content')` sebagai area konten utama.
- Title halaman diambil dari variabel `$title` yang dikirim controller via `$data`.
- Jangan memakai `@yield('title')`, `@section('title')`, atau yield lain selain `@yield('content')`.
- Area konten utama wajib memakai `@section('content')`.
- CSS tambahan per halaman memakai `@push('styles')` dan layout memakai `@stack('styles')`.
- JS tambahan per halaman memakai `@push('scripts')` dan layout memakai `@stack('scripts')`.
- Gunakan helper `asset()` untuk asset publik.
- Jangan memanggil/query model langsung di Blade, terutama melalui `@php` atau `\App\Models\...::get()`. Semua data view wajib dikirim dari controller/service atau `View::composer()` pada service provider.
- Jangan gunakan `{!! !!}` untuk data user kecuali sudah disanitasi/di-escape secara sengaja.

## 5. Controller

- Return view selalu memakai array `$data`.
- `$data` minimal berisi `title` untuk halaman yang memakai layout.
- Jangan memakai `compact()` di controller.
- Validasi request wajib eksplisit.
- Hindari `$request->all()` untuk create/update. Gunakan `$request->only([...])` atau data hasil `$request->validate()`.
- Operasi yang berpotensi gagal karena external service tidak boleh membuat alur utama menjadi 500 tanpa fallback.
- Untuk daftar data besar, gunakan `paginate()`, query terbatas, filter, atau endpoint server-side.

## 5a. Model Convention

- Semua model Eloquent wajib memakai `use HasFactory`.
- Trait lain seperti `LogsActivity`, `HasRoles`, atau `Notifiable` tetap boleh dipakai sesuai kebutuhan model.

## 5b. Penamaan

| Item | Konvensi | Contoh |
| --- | --- | --- |
| View file | `snake_case.blade.php` atau nama resource standar Laravel | `jenis_surat/index.blade.php`, `forgot-password.blade.php` |
| View folder | lowercase sesuai modul existing | `backend/penduduk`, `backend/jenis_surat`, `frontend/pengajuan` |
| Controller | `PascalCase + Controller` | `DashboardController.php`, `JenisSuratController.php` |
| Route name | dot notation | `dashboard`, `public.surat.create`, `jenis-surat.index` |
| Layout file | `app-[nama].blade.php` | `app-backend.blade.php` |

## 5c. Cara Menambah Halaman Backend

1. Buat view di `resources/views/backend/[module]/nama.blade.php`.
2. Extend `layouts.app-backend`.
3. Jangan menulis `@section('title')`; kirim title dari controller.
4. Gunakan `@push('styles')` bila butuh CSS tambahan.
5. Gunakan `@push('scripts')` bila butuh JS tambahan.
6. Controller mengirim `$data` minimal berisi `title`.
7. Tambahkan route di `routes/web.php` dengan middleware auth dan permission sesuai modul.

## 6. Route, Auth, dan Permission

- Route publik tetap tanpa login, tetapi form publik wajib memakai CSRF, validation, dan rate limit bila berisiko spam.
- Route backend wajib berada di middleware `auth`.
- Setiap modul backend harus memakai middleware permission Spatie yang sesuai, bukan hanya menyembunyikan menu.
- Setiap fitur, aksi tombol, request form, validasi, upload, preview, export, test integrasi, dan perubahan status wajib punya permission granular di `database/seeders/RolePermissionSeeder.php` bila dapat dijalankan dari backend.
- Super admin mendapat bypass melalui `Gate::before`, tetapi permission tetap perlu didefinisikan di seeder.
- Logout wajib `POST`.
- Login wajib dilindungi throttle/rate limit.

## 7. RBAC

- Gunakan permission granular, contoh:
  - `dashboard-index`
  - `penduduk-index/create/store/edit/update/destroy`
  - `jenis-surat-index/create/store/edit/update/destroy/template/template-update`
  - `surat-index/create/preview/store/edit/update-status/destroy/show/print`
  - `post-index/create/store/edit/update/destroy`
  - `pengaduan-index/edit/update/destroy`
  - `user-index/create/store/edit/update/destroy`
  - `role-index/show/create/store/edit/update/destroy`
  - `setting-index/update`
  - `report-index/penduduk-excel/surat-excel/surat-pdf/pengaduan-excel`
  - `whatsapp-test-index/send`
  - `profile-index/update`
- Menu sidebar boleh memakai `@can`/`@canany`, tetapi route tetap wajib punya middleware permission.
- Saat menambah role baru, jangan hanya memasukkan nama modul. Sync juga permission aksi yang dibutuhkan agar tombol, form submit, request update, export, dan test integrasi tetap berfungsi sesuai role.
- Permission lama yang masih dipakai sebagai kompatibilitas boleh dipertahankan sementara, tetapi route baru harus memakai permission aksi yang paling spesifik.

## 8. Database dan Transaksi

- Jangan membuat nomor dokumen dengan `count()+1`.
- Nomor surat harus memakai mekanisme counter/sequence atomik dan transaksi.
- Tambahkan index untuk kolom yang sering dipakai filter/sort/join.
- Migration `down()` harus bersih untuk perubahan baru.
- Foreign key penting harus jelas perilaku delete/update-nya.
- Data publik yang masuk tetapi belum diproses admin boleh memiliki `user_id` nullable bila proses bisnis membutuhkannya.

## 9. Upload File

- Gunakan `Storage::disk('public')`.
- Simpan file upload di `storage/app/public/...`, lalu akses melalui `storage/...` setelah `php artisan storage:link`.
- Jangan memakai nama file asli sebagai nama penyimpanan. Gunakan `hashName()`, UUID, atau nama aman lain.
- Validasi file minimal: `image`, `mimes:jpg,jpeg,png,webp`, dan `max:2048` untuk gambar umum.
- Hapus file lama saat diganti bila memang tidak dipakai lagi.
- Jangan memakai disk yang tidak ada di `config/filesystems.php`.

## 10. Notifikasi, Toast, dan Konfirmasi

- Semua flash message dan validation error harus tampil melalui SweetAlert2 toast global.
- Gunakan partial `resources/views/partials/sweetalert.blade.php`.
- Jangan membuat `<div class="alert ...">` untuk flash message.
- Jangan memakai `alert()` atau `confirm()` bawaan browser.
- Konfirmasi delete/aksi berbahaya gunakan form class `js-confirm-submit` dengan `data-confirm-text`.
- SweetAlert2 sudah tersedia di `public/assets/plugins/sweetalert2`.

## 11. External Service dan Queue

- Integrasi Fonnte/WhatsApp harus punya timeout, retry, error logging, dan fallback.
- Notifikasi otomatis yang bukan aksi test langsung harus dikirim lewat queue job.
- Halaman WhatsApp test boleh kirim sinkron agar user melihat hasil langsung.
- Jika queue dipakai, dokumentasikan kebutuhan menjalankan `php artisan queue:work`.

## 12. Export PDF/Excel

- Export data besar gunakan query/chunk/limit, bukan mengambil semua data ke memory tanpa batas.
- PDF report wajib punya filter dan/atau limit.
- Hindari N+1 dengan `with()` untuk relasi yang dibutuhkan.
- Format tanggal di export/user-facing memakai format Indonesia bila relevan.

## 13. UI/UX Backend dan Frontend

- Backend mengikuti AdminLTE 3 dan Bootstrap 4.
- Frontend publik mengikuti Sandbox Bootstrap 5.
- Tombol aksi tabel sebaiknya icon-only dengan `title`.
- Tombol utama di form sebaiknya memakai icon.
- Form penting sebaiknya memakai input group/icon jika sudah menjadi pola modul tersebut.
- Jangan membuat alert inline manual; gunakan toast SweetAlert.
- Jaga tampilan tetap responsif dan tidak memuat data besar sekaligus.

## 14. Testing dan Verifikasi

- Agent boleh melakukan pemeriksaan ringan seperti `php -l` untuk file yang diubah.
- Jika user meminta uji manual dilakukan oleh user, jangan menjalankan test suite/browser/migration tanpa diminta.
- Setiap selesai pengerjaan, laporkan:
  - Sudah dikerjakan
  - Belum dikerjakan/perlu tindak lanjut
  - Arahan uji manual
  - Risiko/catatan bila ada

## 15. Versioning

- Project memakai format versi SemVer: `vMAJOR.MINOR.PATCH`.
- Versi aplikasi disimpan melalui `APP_VERSION` di `.env`/`.env.example` dan dibaca dari `config('app.version')`.
- Setiap perubahan signifikan wajib menaikkan versi:
  - Patch, contoh `v1.0.0` ke `v1.0.1`: bugfix kecil, copy/UI minor, validasi kecil, perubahan non-breaking.
  - Minor, contoh `v1.0.0` ke `v1.1.0`: fitur baru, perubahan UI cukup terasa, flow baru, peningkatan modul yang tidak breaking.
  - Major, contoh `v1.0.0` ke `v2.0.0`: perubahan besar, perubahan struktur/flow utama, perubahan database/permission luas, atau perubahan yang berpotensi breaking.
- Saat menaikkan versi, update minimal `.env.example` dan tempat tampilan versi aplikasi. Jika `.env` lokal ada dan relevan untuk pekerjaan manual, beri arahan agar user menyesuaikan `APP_VERSION`.
- Catat versi lama dan versi baru di laporan akhir pengerjaan.

## 16. Git Workflow

- Setelah perubahan project selesai dan pemeriksaan ringan yang relevan sudah dilakukan, agent wajib membuat commit dan push otomatis ke remote aktif.
- Commit hanya boleh memasukkan file yang terkait dengan pekerjaan saat itu. Jangan staging perubahan user yang tidak terkait, terutama penghapusan massal atau file generated yang belum diminta.
- Jika push gagal karena remote, koneksi, kredensial, atau konflik branch, laporkan penyebabnya dan perubahan yang sudah/ belum masuk remote.
- Pesan commit harus singkat, jelas, dan menjelaskan inti perubahan.

## 17. Governance

- `AGENTS.md` wajib diperbarui saat ada standar project baru.
- Setelah `AGENTS.md` berubah, perubahan kode berikutnya wajib mengikuti dokumen ini.
- `docs/RULES.md` tidak dipakai lagi; dokumen patokan adalah `AGENTS.md` di root project.
