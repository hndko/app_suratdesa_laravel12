<div class="surat-container"
    style="background: white; padding: 2cm; max-width: 21cm; margin: auto; font-family: 'Times New Roman', Times, serif; line-height: 1.5;">

    <!-- KOP SURAT -->
    <div class="kop-surat text-center mb-4" style="border-bottom: 3px double black; padding-bottom: 15px;">
        <div class="row align-items-center">
            <div class="col-2 text-center">
                <!-- Logo Placeholder - Use Absolute Path for Print -->
                <img src="{{ asset('assets/dist/img/AdminLTELogo.png') }}" alt="Logo"
                    style="width: 80px; height: auto;">
            </div>
            <div class="col-10 text-center">
                <h5 class="mb-0 text-uppercase" style="font-weight: bold; font-size: 14pt;">PEMERINTAH KABUPATEN
                    SUKABUMI</h5>
                <h5 class="mb-0 text-uppercase" style="font-weight: bold; font-size: 14pt;">KECAMATAN CISAAT</h5>
                <h4 class="mb-0 text-uppercase" style="font-weight: bold; font-size: 16pt;">DESA SUKAMANTRI</h4>
                <p class="mb-0" style="font-size: 10pt;">Alamat: Jl. Raya Cisaat No. 123 Kode Pos 43152</p>
            </div>
        </div>
    </div>

    <!-- JUDUL SURAT -->
    <div class="judul-surat text-center mb-4">
        <h4 class="mb-0 text-uppercase fw-bold" style="text-decoration: underline;">{{ $header }}</h4>
        <p class="mb-0">Nomor: {{ $nomor_surat }}</p>
    </div>

    <!-- ISI SURAT -->
    <div class="isi-surat text-justify mb-5">
        <p>Yang bertanda tangan di bawah ini Kepala Desa Sukamantri, Kecamatan Cisaat, Kabupaten Sukabumi, menerangkan
            bahwa:</p>

        {!! $content !!}

        <p class="mt-4">Demikian surat keterangan ini dibuat dengan sebenar-benarnya untuk dapat dipergunakan
            sebagaimana mestinya.</p>
    </div>

    <!-- TANDA TANGAN -->
    <div class="tanda-tangan d-flex justify-content-end mt-5">
        <div class="text-center" style="min-width: 250px;">
            <p class="mb-0">Sukamantri, {{ \Carbon\Carbon::parse($tanggal_surat)->isoFormat('D MMMM Y') }}</p>
            <p class="mb-5 fw-bold">Kepala Desa Sukamantri</p>
            <br>
            <br>
            <p class="fw-bold text-uppercase" style="text-decoration: underline;">Nama Kepala Desa</p>
        </div>
    </div>
</div>