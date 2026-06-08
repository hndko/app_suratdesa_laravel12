<?php

namespace Tests\Feature;

use App\Jobs\SendWhatsAppMessage;
use App\Models\JenisSurat;
use App\Models\Penduduk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_and_public_service_pages_are_accessible(): void
    {
        $this->get('/')->assertOk();
        $this->get('/layanan-surat')->assertOk();
        $this->get('/lacak-surat')->assertOk();
        $this->get('/kirim-pengaduan')->assertOk();
        $this->get('/lacak-pengaduan')->assertOk();
    }

    public function test_public_pengaduan_submission_creates_ticket(): void
    {
        Queue::fake();
        Storage::fake('public');

        $response = $this->post('/kirim-pengaduan', [
            'name' => 'Warga Test',
            'nik' => '3201000000000001',
            'phone' => '081234567890',
            'category' => 'pelayanan',
            'content' => 'Mohon tindak lanjut pelayanan administrasi.',
            'image' => UploadedFile::fake()->image('aduan.jpg'),
        ]);

        $response->assertRedirect(route('public.pengaduan.track'));
        $this->assertDatabaseHas('pengaduans', [
            'name' => 'Warga Test',
            'nik' => '3201000000000001',
            'category' => 'pelayanan',
        ]);
        Queue::assertPushed(SendWhatsAppMessage::class);
    }

    public function test_public_surat_submission_creates_tracking_code(): void
    {
        Queue::fake();

        $penduduk = Penduduk::create([
            'nik' => '3201000000000002',
            'nama' => 'Pemohon Test',
            'phone' => '081234567891',
            'tempat_lahir' => 'Bandung',
            'tgl_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Desa',
            'rt' => '001',
            'rw' => '001',
            'agama' => 'Islam',
            'pendidikan' => 'SLTA/Sederajat',
            'shdk' => 'Kepala Keluarga',
            'status_perkawinan' => 'Kawin',
            'pekerjaan' => 'Wiraswasta',
        ]);

        $jenisSurat = JenisSurat::create([
            'kode_surat' => '470',
            'nama_surat' => 'Surat Keterangan Domisili',
            'kop_judul' => 'SURAT KETERANGAN DOMISILI',
            'template_isi' => 'Nama: [nama]',
        ]);

        $response = $this->post('/layanan-surat', [
            'nik' => $penduduk->nik,
            'jenis_surat_id' => $jenisSurat->id,
            'keperluan' => 'Keperluan administrasi',
        ]);

        $response->assertRedirect(route('public.surat.track'));
        $this->assertDatabaseHas('surats', [
            'penduduk_id' => $penduduk->id,
            'jenis_surat_id' => $jenisSurat->id,
            'status' => 'pending',
        ]);
        $this->assertNotNull($penduduk->surats()->first()->tracking_code);
        Queue::assertPushed(SendWhatsAppMessage::class);
    }
}
