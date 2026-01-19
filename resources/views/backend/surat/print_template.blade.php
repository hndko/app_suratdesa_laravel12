<div class="surat-container"
    style="background: white; padding: 2cm; max-width: 21cm; margin: auto; font-family: 'Times New Roman', Times, serif; line-height: 1.5; color: #000;">

    <!-- KOP SURAT (Table for better alignment) -->
    <table style="width: 100%; border-bottom: 3px double black; margin-bottom: 20px;">
        <tr>
            <td style="width: 15%; text-align: center; vertical-align: middle; padding-bottom: 15px;">
                <img src="{{ asset('assets/dist/img/AdminLTELogo.png') }}" alt="Logo"
                    style="width: 90px; height: auto;">
            </td>
            <td style="width: 85%; text-align: center; vertical-align: middle; padding-bottom: 15px;">
                <h5 class="mb-0 text-uppercase" style="font-weight: bold; font-size: 14pt; margin: 0;">PEMERINTAH
                    KABUPATEN SUKABUMI</h5>
                <h5 class="mb-0 text-uppercase" style="font-weight: bold; font-size: 14pt; margin: 0;">KECAMATAN CISAAT
                </h5>
                <h4 class="mb-0 text-uppercase" style="font-weight: bold; font-size: 16pt; margin: 0;">DESA SUKAMANTRI
                </h4>
                <p class="mb-0" style="font-size: 11pt; margin: 0;">Alamat: Jl. Raya Cisaat No. 123 Kode Pos 43152</p>
            </td>
        </tr>
    </table>

    <!-- JUDUL SURAT -->
    <div class="judul-surat text-center mb-4">
        <h4 class="mb-0 text-uppercase fw-bold" style="text-decoration: underline; font-size: 14pt;">{{ $header }}</h4>
        <p class="mb-0" style="font-size: 11pt;">Nomor: {{ $nomor_surat }}</p>
    </div>

    <!-- ISI SURAT -->
    <div class="isi-surat text-justify mb-5" style="font-size: 12pt;">
        <p>Yang bertanda tangan di bawah ini Kepala Desa Sukamantri, Kecamatan Cisaat, Kabupaten Sukabumi, menerangkan
            bahwa:</p>

        {!! $content !!}

        <p class="mt-4">Demikian surat keterangan ini dibuat dengan sebenar-benarnya untuk dapat dipergunakan
            sebagaimana mestinya.</p>
    </div>

    <!-- TANDA TANGAN -->
    <div class="tanda-tangan d-flex justify-content-end mt-5" style="font-size: 12pt;">
        <div class="text-center" style="min-width: 250px;">
            <p class="mb-0">Sukamantri, {{ \Carbon\Carbon::parse($tanggal_surat)->isoFormat('D MMMM Y') }}</p>
            <p class="mb-5 fw-bold">Kepala Desa Sukamantri</p>
            <br>
            <br>
            <p class="fw-bold text-uppercase" style="text-decoration: underline; margin-bottom: 0;">Nama Kepala Desa</p>
        </div>
    </div>
</div>