@extends('layouts.app-backend')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Kirim Pesan Uji Coba</h3>
                        </div>
                        <form action="{{ route('whatsapp.test.send') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <p class="text-muted">
                                    Gunakan formulir ini untuk memastikan integrasi dengan <strong>Fonnte API</strong> sudah berjalan dengan benar.
                                </p>
                                <div class="form-group">
                                    <label for="phone">Nomor WhatsApp Tujuan</label>
                                    <input type="text" name="phone" id="phone" class="form-control" placeholder="Contoh: 08123456789" required>
                                    <small class="text-muted">Gunakan format internasional atau lokal (08...)</small>
                                </div>
                                <div class="form-group">
                                    <label for="message">Pesan</label>
                                    <textarea name="message" id="message" rows="4" class="form-control" required>Halo! Ini adalah pesan uji coba dari sistem SIMADES.</textarea>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane mr-1"></i> Kirim Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Koneksi</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="30%"><strong>Provider</strong></td>
                                    <td>: Fonnte (api.fonnte.com)</td>
                                </tr>
                                <tr>
                                    <td><strong>Status Token</strong></td>
                                    <td>: 
                                        @if(config('services.fonnte.token'))
                                            <span class="badge badge-success">Terpasang (v)</span>
                                        @else
                                            <span class="badge badge-danger">Belum Diatur (x)</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Versi API</strong></td>
                                    <td>: v1.0</td>
                                </tr>
                            </table>
                            <hr>
                            <h5>Tips:</h5>
                            <ul class="text-sm">
                                <li>Pastikan token Fonnte di file <code>.env</code> sudah benar.</li>
                                <li>Pastikan nomor tujuan aktif WhatsApp-nya.</li>
                                <li>Jika menggunakan akun trial Fonnte, biasanya hanya bisa mengirim ke nomor yang terdaftar di kontak.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
