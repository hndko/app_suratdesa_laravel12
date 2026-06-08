<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $header }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 85%;
            margin: auto;
            padding: 20px;
        }
        .judul-surat {
            text-align: center;
            margin-bottom: 20px;
        }
        .judul-surat h4 {
            margin: 0;
            text-decoration: underline;
            font-size: 14pt;
        }
        .judul-surat p {
            margin: 0;
        }
        table {
            border-collapse: collapse;
        }
        .identity-table td {
            padding: 2px 0;
        }
        p {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        @include('backend.surat.parts.kop_surat')

        <div class="judul-surat">
            <h4>{{ $header }}</h4>
            <p>Nomor: {{ $nomor_surat }}</p>
        </div>

        <div class="isi-surat">
            {!! $content !!}
        </div>

        @include('backend.surat.parts.tanda_tangan')

        @if(!empty($verification))
        <div style="margin-top: 30px; border-top: 1px solid #ddd; padding-top: 10px; font-size: 9pt;">
            <strong>Verifikasi Surat:</strong> {{ $verification->verification_code }}<br>
            <span>{{ route('public.surat.verify') }}</span><br>
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ urlencode(route('public.surat.verify') . '?code=' . $verification->verification_code) }}" alt="QR Verifikasi" style="margin-top: 6px;">
        </div>
        @endif
    </div>
</body>
</html>
