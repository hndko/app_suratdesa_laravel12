<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; padding: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .meta { margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>PEMERINTAH KABUPATEN ...</h2>
        <h3>KECAMATAN ...</h3>
        <h2>DESA {{ strtoupper(\App\Facades\Setting::get('village_name', '...')) }}</h2>
        <hr>
        <h4>{{ strtoupper($title) }}</h4>
    </div>

    <div class="meta">
        @if($startDate && $endDate)
            Periode: {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Surat</th>
                <th>NIK</th>
                <th>Nama Penduduk</th>
                <th>Jenis Surat</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($surats as $index => $surat)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $surat->no_surat }}</td>
                <td>{{ $surat->penduduk->nik ?? '-' }}</td>
                <td>{{ $surat->penduduk->nama ?? '-' }}</td>
                <td>{{ $surat->jenisSurat->nama_surat ?? '-' }}</td>
                <td>{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                <td>{{ strtoupper($surat->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right;">
        <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
