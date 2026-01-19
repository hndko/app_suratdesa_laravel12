<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kode_surat' => '470',
                'nama_surat' => 'Surat Keterangan Domisili',
                'kop_judul' => 'SURAT KETERANGAN DOMISILI',
                'template_isi' => "Yang bertanda tangan di bawah ini Kepala Desa, menerangkan bahwa:\n\nNama: [nama]\nNIK: [nik]\nTempat/Tgl Lahir: [tempat_lahir], [tgl_lahir]\nAlamat: [alamat]\n\nAdalah benar-benar warga penduduk desa kami yang berdomisili di alamat tersebut di atas.\n\nDemikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.",
            ],
            [
                'kode_surat' => '500',
                'nama_surat' => 'Surat Keterangan Usaha',
                'kop_judul' => 'SURAT KETERANGAN USAHA',
                'template_isi' => "Yang bertanda tangan di bawah ini Kepala Desa, menerangkan bahwa:\n\nNama: [nama]\nNIK: [nik]\nAlamat: [alamat]\n\nBenar nama tersebut di atas mempunyai usaha ...................................... yang berlokasi di ......................................\n\nDemikian surat keterangan ini dibuat untuk persyaratan ......................................",
            ],
            [
                'kode_surat' => '401',
                'nama_surat' => 'Surat Keterangan Tidak Mampu',
                'kop_judul' => 'SURAT KETERANGAN TIDAK MAMPU',
                'template_isi' => "Yang bertanda tangan di bawah ini Kepala Desa, menerangkan bahwa:\n\nNama: [nama]\nNIK: [nik]\nPekerjaan: [pekerjaan]\nAlamat: [alamat]\n\nAdalah benar warga yang tergolong keluarga tidak mampu.\n\nDemikian surat keterangan ini diperbuat untuk melengkapi persyaratan ......................................",
            ],
            [
                'kode_surat' => '474',
                'nama_surat' => 'Surat Keterangan Kelakuan Baik',
                'kop_judul' => 'SURAT KETERANGAN KELAKUAN BAIK',
                'template_isi' => "Yang bertanda tangan di bawah ini Kepala Desa, menerangkan bahwa:\n\nNama: [nama]\nNIK: [nik]\nAgama: [agama]\nAlamat: [alamat]\n\nSepanjang pengetahuan kami, nama tersebut berkelakuan baik dan tidak pernah tersangkut perkara pidana.\n\nDemikian surat keterangan ini dibuat dengan sebenarnya.",
            ],
            [
                'kode_surat' => '471',
                'nama_surat' => 'Surat Pengantar KTP',
                'kop_judul' => 'SURAT PENGANTAR KTP',
                'template_isi' => "Yang bertanda tangan di bawah ini Kepala Desa, menerangkan permohonan penerbitan KTP untu:\n\nNama: [nama]\nNIK: [nik]\nTTL: [tempat_lahir], [tgl_lahir]\n\nDemikian pengantar ini dibuat untuk dapat diproses di Kecamatan/Dinas Kependudukan.",
            ],
        ];

        foreach ($data as $item) {
            JenisSurat::create($item);
        }
    }
}
