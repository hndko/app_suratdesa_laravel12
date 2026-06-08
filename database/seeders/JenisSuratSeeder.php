<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use Illuminate\Database\Seeder;

class JenisSuratSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'kode_surat' => '470',
                'nama_surat' => 'Surat Keterangan Domisili',
                'kop_judul' => 'SURAT KETERANGAN DOMISILI',
                'template_isi' => "Yang bertanda tangan di bawah ini Kepala Desa, menerangkan bahwa:\n\nNama: [nama]\nNIK: [nik]\nTempat/Tgl Lahir: [tempat_lahir], [tgl_lahir]\nAlamat: [alamat]\n\nAdalah benar warga desa kami dan berdomisili pada alamat tersebut.\n\nDemikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.",
            ],
            [
                'kode_surat' => '500',
                'nama_surat' => 'Surat Keterangan Usaha',
                'kop_judul' => 'SURAT KETERANGAN USAHA',
                'template_isi' => "Yang bertanda tangan di bawah ini Kepala Desa, menerangkan bahwa:\n\nNama: [nama]\nNIK: [nik]\nAlamat: [alamat]\nPekerjaan: [pekerjaan]\n\nBenar yang bersangkutan mempunyai usaha sebagaimana diterangkan dalam keperluan surat ini.\n\nDemikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.",
            ],
            [
                'kode_surat' => '401',
                'nama_surat' => 'Surat Keterangan Tidak Mampu',
                'kop_judul' => 'SURAT KETERANGAN TIDAK MAMPU',
                'template_isi' => "Yang bertanda tangan di bawah ini Kepala Desa, menerangkan bahwa:\n\nNama: [nama]\nNIK: [nik]\nPekerjaan: [pekerjaan]\nAlamat: [alamat]\n\nAdalah benar warga desa kami yang tergolong keluarga tidak mampu.\n\nDemikian surat keterangan ini dibuat untuk melengkapi persyaratan administrasi.",
            ],
            [
                'kode_surat' => '474',
                'nama_surat' => 'Surat Keterangan Kelakuan Baik',
                'kop_judul' => 'SURAT KETERANGAN KELAKUAN BAIK',
                'template_isi' => "Yang bertanda tangan di bawah ini Kepala Desa, menerangkan bahwa:\n\nNama: [nama]\nNIK: [nik]\nAgama: [agama]\nAlamat: [alamat]\n\nSepanjang pengetahuan kami, yang bersangkutan berkelakuan baik dan tidak pernah tersangkut perkara pidana di desa.\n\nDemikian surat keterangan ini dibuat dengan sebenarnya.",
            ],
            [
                'kode_surat' => '471',
                'nama_surat' => 'Surat Pengantar KTP',
                'kop_judul' => 'SURAT PENGANTAR KTP',
                'template_isi' => "Yang bertanda tangan di bawah ini Kepala Desa, menerangkan permohonan penerbitan KTP untuk:\n\nNama: [nama]\nNIK: [nik]\nTTL: [tempat_lahir], [tgl_lahir]\nAlamat: [alamat]\n\nDemikian pengantar ini dibuat untuk dapat diproses di instansi terkait.",
            ],
            [
                'kode_surat' => '472',
                'nama_surat' => 'Surat Pengantar Kartu Keluarga',
                'kop_judul' => 'SURAT PENGANTAR KARTU KELUARGA',
                'template_isi' => "Yang bertanda tangan di bawah ini Kepala Desa, memberikan pengantar pengurusan Kartu Keluarga untuk:\n\nNama: [nama]\nNIK: [nik]\nAlamat: [alamat]\n\nDemikian surat pengantar ini dibuat untuk dipergunakan sebagaimana mestinya.",
            ],
            [
                'kode_surat' => '475',
                'nama_surat' => 'Surat Keterangan Kelahiran',
                'kop_judul' => 'SURAT KETERANGAN KELAHIRAN',
                'template_isi' => "Yang bertanda tangan di bawah ini Kepala Desa, menerangkan data pelapor:\n\nNama: [nama]\nNIK: [nik]\nAlamat: [alamat]\n\nKeterangan kelahiran sebagaimana terlampir pada keperluan surat ini.\n\nDemikian surat ini dibuat untuk proses administrasi kependudukan.",
            ],
            [
                'kode_surat' => '476',
                'nama_surat' => 'Surat Keterangan Kematian',
                'kop_judul' => 'SURAT KETERANGAN KEMATIAN',
                'template_isi' => "Yang bertanda tangan di bawah ini Kepala Desa, menerangkan data pelapor:\n\nNama: [nama]\nNIK: [nik]\nAlamat: [alamat]\n\nKeterangan kematian sebagaimana terlampir pada keperluan surat ini.\n\nDemikian surat ini dibuat untuk proses administrasi kependudukan.",
            ],
            [
                'kode_surat' => '477',
                'nama_surat' => 'Surat Keterangan Pindah',
                'kop_judul' => 'SURAT KETERANGAN PINDAH',
                'template_isi' => "Yang bertanda tangan di bawah ini Kepala Desa, menerangkan bahwa:\n\nNama: [nama]\nNIK: [nik]\nTTL: [tempat_lahir], [tgl_lahir]\nAlamat: [alamat]\n\nYang bersangkutan mengajukan keterangan pindah sesuai keperluan yang tercantum.\n\nDemikian surat ini dibuat untuk dipergunakan sebagaimana mestinya.",
            ],
            [
                'kode_surat' => '478',
                'nama_surat' => 'Surat Keterangan Belum Menikah',
                'kop_judul' => 'SURAT KETERANGAN BELUM MENIKAH',
                'template_isi' => "Yang bertanda tangan di bawah ini Kepala Desa, menerangkan bahwa:\n\nNama: [nama]\nNIK: [nik]\nTTL: [tempat_lahir], [tgl_lahir]\nStatus Perkawinan: [status_perkawinan]\nAlamat: [alamat]\n\nMenurut data dan keterangan yang ada, yang bersangkutan belum menikah.\n\nDemikian surat keterangan ini dibuat dengan sebenarnya.",
            ],
            [
                'kode_surat' => '479',
                'nama_surat' => 'Surat Keterangan Ahli Waris',
                'kop_judul' => 'SURAT KETERANGAN AHLI WARIS',
                'template_isi' => "Yang bertanda tangan di bawah ini Kepala Desa, menerangkan data pemohon:\n\nNama: [nama]\nNIK: [nik]\nAlamat: [alamat]\n\nKeterangan ahli waris sebagaimana tercantum pada keperluan dan lampiran pendukung.\n\nDemikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.",
            ],
            [
                'kode_surat' => '480',
                'nama_surat' => 'Surat Keterangan Penghasilan',
                'kop_judul' => 'SURAT KETERANGAN PENGHASILAN',
                'template_isi' => "Yang bertanda tangan di bawah ini Kepala Desa, menerangkan bahwa:\n\nNama: [nama]\nNIK: [nik]\nPekerjaan: [pekerjaan]\nAlamat: [alamat]\n\nKeterangan penghasilan yang bersangkutan tercantum dalam keperluan surat ini.\n\nDemikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.",
            ],
            [
                'kode_surat' => '481',
                'nama_surat' => 'Surat Keterangan Beda Nama',
                'kop_judul' => 'SURAT KETERANGAN BEDA NAMA',
                'template_isi' => "Yang bertanda tangan di bawah ini Kepala Desa, menerangkan bahwa:\n\nNama: [nama]\nNIK: [nik]\nTTL: [tempat_lahir], [tgl_lahir]\nAlamat: [alamat]\n\nKeterangan beda nama yang bersangkutan dijelaskan pada keperluan surat ini.\n\nDemikian surat keterangan ini dibuat dengan sebenarnya.",
            ],
            [
                'kode_surat' => '482',
                'nama_surat' => 'Surat Keterangan Umum',
                'kop_judul' => 'SURAT KETERANGAN',
                'template_isi' => "Yang bertanda tangan di bawah ini Kepala Desa, menerangkan bahwa:\n\nNama: [nama]\nNIK: [nik]\nTTL: [tempat_lahir], [tgl_lahir]\nAlamat: [alamat]\n\nKeterangan yang dimohon tercantum dalam keperluan surat ini.\n\nDemikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.",
            ],
            [
                'kode_surat' => '483',
                'nama_surat' => 'Surat Rekomendasi Desa',
                'kop_judul' => 'SURAT REKOMENDASI DESA',
                'template_isi' => "Yang bertanda tangan di bawah ini Kepala Desa, memberikan rekomendasi kepada:\n\nNama: [nama]\nNIK: [nik]\nAlamat: [alamat]\nPekerjaan: [pekerjaan]\n\nRekomendasi diberikan sesuai keperluan yang tercantum pada surat ini.\n\nDemikian surat rekomendasi ini dibuat untuk dipergunakan sebagaimana mestinya.",
            ],
        ];

        foreach ($data as $item) {
            JenisSurat::updateOrCreate(
                ['kode_surat' => $item['kode_surat']],
                $item
            );
        }
    }
}
