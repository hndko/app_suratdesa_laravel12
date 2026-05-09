<div class="kop-surat" style="text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px;">
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 80px; vertical-align: middle;">
                <img src="{{ public_path(\App\Facades\Setting::get('village_logo', 'assets/img/logo.png')) }}" alt="Logo" style="width: 80px; height: auto;">
            </td>
            <td style="text-align: center; vertical-align: middle;">
                <h4 style="margin: 0; text-transform: uppercase; font-size: 14pt; letter-spacing: 1px;">PEMERINTAH {{ \App\Facades\Setting::get('village_kabupaten', config('village.kabupaten')) }}</h4>
                <h4 style="margin: 0; text-transform: uppercase; font-size: 14pt; letter-spacing: 1px;">{{ \App\Facades\Setting::get('village_kecamatan', config('village.kecamatan')) }}</h4>
                <h3 style="margin: 0; text-transform: uppercase; font-size: 18pt; font-weight: bold; color: #000;">{{ \App\Facades\Setting::get('village_nama', config('village.nama_desa')) }}</h3>
                <p style="margin: 0; font-size: 10pt; font-style: italic;">{{ \App\Facades\Setting::get('village_alamat', config('village.alamat')) }}</p>
                <p style="margin: 0; font-size: 10pt;">Telp: {{ \App\Facades\Setting::get('village_telepon', config('village.telepon')) }} | Email: {{ \App\Facades\Setting::get('village_email', config('village.email')) }} | Website: {{ \App\Facades\Setting::get('village_website', config('village.website')) }}</p>
            </td>
            <td style="width: 80px;"></td> <!-- Spacer to center text -->
        </tr>
    </table>
</div>
