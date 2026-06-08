# RULES - Aturan Penulisan Project

Dokumen ini berisi aturan penulisan dan konvensi kode untuk project agar konsisten di antara semua developer/agent.

---

## 1. Template & Layout

- **Assets lokasi**: `public/assets/` (CSS, JS, images, libs, fonts)

### Layout Files

| Layout  | File                            | Penggunaan                                                |
| ------- | ------------------------------- | --------------------------------------------------------- |
| Backend | `layouts/app-backend.blade.php` | Halaman yang butuh sidebar (dashboard, CRUD, dll)         |
| Auth    | `layouts/app-auth.blade.php`    | Halaman authentication (login, register, forgot password) |

### Aturan Layout

- **JANGAN membuat partials** (topbar, sidebar, footer terpisah). Semua komponen layout harus **digabung dalam satu file layout**.
- Layout hanya menyediakan **`@yield('content')`** sebagai area konten utama.
- **Title halaman** diambil dari variabel `$title` yang dikirim controller via `$data`, bukan `@yield('title')`.
    ```blade
    <title>{{ $title ?? 'Dashboard' }} | POS Clone Accurate</title>
    ```
- Untuk CSS tambahan per-halaman: gunakan **`@push('styles')`** dan **`@stack('styles')`**.
- Untuk JS tambahan per-halaman: gunakan **`@push('scripts')`** dan **`@stack('scripts')`**.
- **JANGAN** menggunakan `@yield('title')`, `@section('title')`, atau yield lain selain `@yield('content')`.
- **DILARANG KERAS** memanggil/query Model (`\App\Models\...::get()`) langsung di dalam view menggunakan `@php`. Semua data yang dibutuhkan view **WAJIB** dikirim dari Controller atau diletakkan di dalam `View::composer()` pada _Service Provider_ (contoh: `AppServiceProvider`).

---

## 2. Struktur Folder

### Views (`resources/views/`)

```
views/
├── layouts/
│   ├── app-backend.blade.php    ← Layout backend (sidebar, topbar, footer)
│   └── app-auth.blade.php       ← Layout auth (minimal)
├── backend/
│   ├── dashboard.blade.php      ← Halaman dashboard
│   └── [module_name]/           ← Folder per modul (contoh: produk/, transaksi/)
│       ├── index.blade.php
│       ├── create.blade.php
│       ├── edit.blade.php
│       └── show.blade.php
└── auth/
    ├── login.blade.php
    ├── register.blade.php
    └── forgot-password.blade.php
```

### Controllers (`app/Http/Controllers/`)

```
Controllers/
├── Backend/
│   ├── DashboardController.php
│   └── [ModuleName]Controller.php
└── Auth/
    └── AuthController.php
```

---

## 3. Konvensi Penamaan

| Item              | Konvensi                  | Contoh                                   |
| ----------------- | ------------------------- | ---------------------------------------- |
| View file         | `kebab-case.blade.php`    | `forgot-password.blade.php`              |
| View folder       | `lowercase`               | `backend/`, `auth/`                      |
| Controller        | `PascalCase + Controller` | `DashboardController.php`                |
| Controller folder | `PascalCase`              | `Backend/`, `Auth/`                      |
| Route name        | `dot.notation`            | `dashboard`, `login`, `password.request` |
| Layout file       | `app-[nama].blade.php`    | `app-backend.blade.php`                  |

---

## 4. Cara Menambah Halaman Baru (Backend)

1. Buat file view di `resources/views/backend/[module]/nama.blade.php`
2. Extend layout backend (**tanpa** `@section('title')`):

    ```blade
    @extends('layouts.app-backend')

    @section('content')
        <!-- konten halaman -->
    @endsection
    ```

3. Jika butuh CSS tambahan: gunakan `@push('styles')` di view
4. Jika butuh JS tambahan: gunakan `@push('scripts')` di view
5. Buat controller di `app/Http/Controllers/Backend/`, gunakan pola `$data`:

    ```php
    public function index()
    {
        $data = [
            'title' => 'Nama Halaman',
        ];

        return view('backend.module.index', $data);
    }
    ```

6. Tambahkan route di `routes/web.php`

---

## 4a. Model Convention

- Semua model **WAJIB** menggunakan `use HasFactory`
- **JANGAN** gunakan `compact()` di controller, gunakan `$data` array

```php
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NamaModel extends Model
{
    use HasFactory;
}
```

## 4b. Controller Convention

- Return view **SELALU** gunakan `$data` array, **JANGAN** gunakan `compact()`
- `$data` minimal berisi `title` untuk judul halaman

```php
public function index()
{
    $data = [
        'title' => 'Judul Halaman',
        'items' => Item::all(),
    ];

    return view('backend.module.index', $data);
}
```

---

## 5. Cara Menambah Menu Sidebar

Edit file `resources/views/layouts/app-backend.blade.php`, cari bagian `<!-- Left Menu Start -->` dan tambahkan item menu baru:

```blade
<li>
    <a href="{{ route('nama.route') }}" class="waves-effect">
        <i class="ti-[icon]"></i>
        <span>Nama Menu</span>
    </a>
</li>
```

Untuk menu dengan sub-menu:

```blade
<li>
    <a href="javascript: void(0);" class="has-arrow waves-effect">
        <i class="ti-[icon]"></i>
        <span>Nama Menu</span>
    </a>
    <ul class="sub-menu" aria-expanded="false">
        <li><a href="{{ route('sub.route') }}">Sub Menu</a></li>
    </ul>
</li>
```

---

## 6. Asset Referencing

- **SELALU** gunakan helper `asset()` untuk referensi file statis:
    ```blade
    {{ asset('assets/css/app.min.css') }}
    {{ asset('assets/images/logo-sm.png') }}
    ```
- **JANGAN** gunakan path relatif langsung.

---

## 7. Icon Libraries

Template NiceAdmin UTAMA menggunakan:

- **Bootstrap Icons**: `bi bi-*` (digunakan di sidebar, topbar & konten secara default)

Library opsional lainnya (jika masih tersisa dari Veltrix):

- **Themify Icons**: `ti-*`
- **Material Design Icons**: `mdi mdi-*`
- **Font Awesome**: `fas fa-*`, `far fa-*`
- **Ion Icons**: `ion ion-*`
- **Dripicons**: `dripicons-*`

---

## 8. Stack Conventions

| Stack Name | Lokasi di Layout                    | Penggunaan           |
| ---------- | ----------------------------------- | -------------------- |
| `styles`   | Di `<head>`, sebelum Bootstrap CSS  | CSS spesifik halaman |
| `scripts`  | Di akhir `<body>`, setelah `app.js` | JS spesifik halaman  |

---

## 9. Authentication

- **Package**: Laravel built-in Auth (`Auth::attempt`, `Auth::logout`)
- **Password Hashing**: Gunakan `Hash::make()`, BUKAN `bcrypt()`. Model User sudah menggunakan cast `'password' => 'hashed'`.
- **Login route**: `GET /login` (form) + `POST /login` (proses)
- **Logout route**: `POST /logout` (wajib POST, bukan GET)
- **Tidak ada** route register dan forgot-password (user dibuat oleh Owner/Admin)
- **Default users** (via seeder):
    - Owner: `owner@example.com` / `password`
    - Admin: `admin@example.com` / `password`

---

## 10. RBAC (Role-Based Access Control)

- **Package**: `spatie/laravel-permission`
- **4 Role**: Owner, Admin, Kasir, Sales
- **Pengecekan role di Blade**:
    ```blade
    @role('Owner')
        <!-- hanya tampil untuk Owner -->
    @endrole
    ```
- **Pengecekan role di Controller**:
    ```php
    if (auth()->user()->hasRole('Owner')) { ... }
    ```
- **Audit Log**: `spatie/laravel-activitylog` — otomatis mencatat perubahan pada model `User`

---

## 11. Route Conventions

- **Middleware `guest`**: Route auth (login) — redirect ke dashboard jika sudah login
- **Middleware `auth`**: Route backend — redirect ke login jika belum login
- **Gunakan `prefix` + `name` grouping** untuk route berkelompok:
    ```php
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
    });
    ```

---

## 12. File Upload

- **Disk**: Gunakan `Storage::disk('public')` dan pastikan sudah run `php artisan storage:link`
- **Path Penyimpanan**:
    - Avatar user: `storage/app/public/avatars/`
    - Logo aplikasi: `storage/app/public/settings/`
- **Format**: JPG, JPEG, PNG — maksimum 2MB
- **Akses & Pengecekan di Blade (Fallback Image)**:
  Wajib melakukan pengecekan file fisik menggunakan `\Storage::disk('public')->exists()`. Jika null / tidak ada, gunakan default image.
    ```blade
    @if(Auth::user()->avatar && \Storage::disk('public')->exists('avatars/' . Auth::user()->avatar))
        <img src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}" alt="Avatar">
    @else
        <img src="{{ asset('assets/images/users/user-4.jpg') }}" alt="Default Avatar">
    @endif
    ```

---

## 13. Alerts & Notifications

- **Flash Messages (Success/Error)**: Gunakan **iziToast**. Layout sudah dikonfigurasi untuk menampilkan `session('success')`, `session('error')`, dan `$errors` validasi secara otomatis menggunakan iziToast.
- **JANGAN** gunakan alert bawaan Bootstrap (`<div class="alert alert-success">...</div>`) di dalam view untuk flash message.
- **Notifikasi Berhasil/Gagal**: Gunakan **iziToast**.
- **Konfirmasi Aksi (misal Delete)**: Gunakan **SweetAlert2** (`Swal.fire`).

---

## 15. Standardisasi Rendering Tabel (List Data)

- **Wajib Server-Side**: Semua halaman yang menampilkan daftar data (index) **HARUS** menggunakan implementasi Server-Side Processing dengan **jQuery DataTables** dan package **`yajra/laravel-datatables-oracle`**.
- **JANGAN** menggunakan `@foreach` murni dari Blade jika datanya berpotensi besar (contoh: referensi master, produk, transaksi).
- Controller akan merespons `$request->ajax()` dengan struktur JSON `DataTables::of()->make(true)` dan blade hanya memuat kerangka tabel kosong `<tbody></tbody>` yang diinisialisasi melalui jQuery `$('#dataTable').DataTable({...})`.
- **Aksi Tabel**: Render tombol edit & delete langsung ke dalam fungsi `addColumn('action', ...)` di Controller, dan pastikan event listener di Blade menggunakan _event delegation_ (contoh: `$('#dataTable').on('click', '.btn-delete', ...)`) karena manipulasi DOM berjalan asinkron.
- **Bahasa DataTables**: **JANGAN** gunakan konfigurasi `language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' }` pada module mana pun. Gunakan konfigurasi default yang sudah berjalan di project.

---

## 14. Global Configuration (App Name dll)

- **Nama Aplikasi**: Di file Blade (title, footer, copy), **SELALU** gunakan `{{ config('app.name') }}`.
- **Konfigurasi Dinamis**: `config('app.name')` akan secara otomatis dioverride (ditimpa) dengan nilai dari database `settings` (`app_name`) saat aplikasi diload, melalui `App\Providers\AppServiceProvider`.
- **Performa Database**: Supaya query `Setting` tidak berjalan berkali-kali pada satu halaman dan menyebabkan N+1 queries, maka model `Setting` menggunakan **Laravel Cache** (`Cache::rememberForever`). Saat pengaturan disave via controller, panggil `Cache::forget('setting_key')` untuk mereset cache agar perubahan langsung tampil.

---

## 15. UI / UX Conventions (Forms & Buttons)

- **Forms (Input Fields)**:
    - Setiap input field **WAJIB** memiliki attribute `placeholder`.
    - Jika memungkinkan/relevan (terutama di auth atau form penting), tambahkan **icon** di dalam input field (menggunakan input group / form group icon).
    - Untuk halaman **create/edit** backend, jadikan `backend/[module]/create.blade.php` dan `backend/[module]/edit.blade.php` sebagai pola utama (title + breadcrumb + card title + komponen form konsisten).
    - Field `input`, `textarea`, dan `select` yang relevan **diutamakan** menggunakan pola `input-group` + icon agar seragam antar modul.
- **Buttons**:
    - Setiap button **WAJIB** memiliki **icon** (menggunakan library icon yang tersedia, eg: `mdi mdi-`, `fas fa-`, `ti-`).
    - Untuk **Action Buttons** yang berada di dalam tabel (misal Edit, Delete, Show), button **HANYA** boleh menampilkan icon saja (tidak ada text), untuk menghemat space tabel. Lengkapi button ini dengan atribut `title` atau tooltip bila diperlukan.

---

## 16. Input Format & Masking (Numeric & Mata Uang)

- **UX Harga / Angka**: Setiap form yang memerlukan input angka nominal uang (seperti Harga Beli, Harga Jual) HARUS dilindungi dengan format ribuan secara visual agar memudahkan pengguna (misal: `2.000.000` alih-alih `2000000`).
- **Library**: Gunakan `Cleave.js` yang sudah dilampirkan via CDN di dalam layout utama (`app-backend.blade.php`).
- **Implementasi Form**:
    1. Tambahkan CSS custom class: `.cleave-currency` pada element `<input>`.
    2. Ubah tipe input dari `type="number"` menjadi `type="text"`.
    3. Di bagian `@push('scripts')`, jalankan inisialisasi `.cleave-currency` dan re-format (hilangkan tanda baca separator) sebelum di-submit via listener _event submit form_ JavaScript, agar request yang dikirim ke controller adalah nominal integer murni.

---

## 17. Format Tanggal & Waktu (Indonesian Locale)

- **Library**: Gunakan `Carbon` bawaan Laravel. Aplikasi sudah di-set ke `timezone='Asia/Jakarta'` dan `locale='id'`.
- **Penggunaan**: Untuk menampilkan tanggal ke user dalam format Indonesia (misal: "06 Maret 2026"), **SELALU** gunakan method `isoFormat()`. Hindari `format()` biasa karena tidak me-load locale bahasa.
- **Standar Format**:
    - Tanggal saja: `isoFormat('D MMMM YYYY')` (contoh: 6 Maret 2026)
    - Tanggal & Waktu: `isoFormat('D MMMM YYYY, HH:mm')` (contoh: 6 Maret 2026, 14:30)
- **Implementasi Controller (DataTables)**:
    ```php
    ->addColumn('created_at', function ($row) {
        return $row->created_at ? $row->created_at->isoFormat('D MMMM YYYY, HH:mm') : '-';
    })
    ```
- **Implementasi Blade**:
    ```blade
    {{ $purchase->purchase_date->isoFormat('D MMMM YYYY') }}
    ```

---

## 18. Governance Aturan Baru

- Setiap ada keputusan/penyesuaian standar baru project, aturan tersebut **WAJIB langsung ditambahkan** ke `docs/RULES.md` pada sesi yang sama.
- Jika aturan baru berdampak lintas modul (misal style DataTables, style form, alert, struktur header), lakukan update rule dulu sebelum melanjutkan implementasi modul lain.
