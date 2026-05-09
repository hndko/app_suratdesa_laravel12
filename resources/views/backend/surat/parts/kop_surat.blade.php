<div class="kop-surat" style="text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px;">
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 80px; vertical-align: middle;">
                <img src="{{ public_path(config('village.logo')) }}" alt="Logo" style="width: 80px; height: auto;">
            </td>
            <td style="text-align: center; vertical-align: middle;">
                <h4 style="margin: 0; text-transform: uppercase; font-size: 14pt; letter-spacing: 1px;">PEMERINTAH {{ config('village.kabupaten') }}</h4>
                <h4 style="margin: 0; text-transform: uppercase; font-size: 14pt; letter-spacing: 1px;">{{ config('village.kecamatan') }}</h4>
                <h3 style="margin: 0; text-transform: uppercase; font-size: 18pt; font-weight: bold; color: #000;">{{ config('village.nama_desa') }}</h3>
                <p style="margin: 0; font-size: 10pt; font-style: italic;">{{ config('village.alamat') }}</p>
                <p style="margin: 0; font-size: 10pt;">Telp: {{ config('village.telepon') }} | Email: {{ config('village.email') }} | Website: {{ config('village.website') }}</p>
            </td>
            <td style="width: 80px;"></td> <!-- Spacer to center text -->
        </tr>
    </table>
</div>
