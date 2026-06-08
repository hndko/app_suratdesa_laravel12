<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kartu_keluargas', function (Blueprint $table) {
            $table->id();
            $table->string('no_kk', 16)->unique();
            $table->string('kepala_keluarga');
            $table->text('alamat');
            $table->string('rt', 3);
            $table->string('rw', 3);
            $table->string('desa')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->timestamps();

            $table->index(['kepala_keluarga', 'no_kk']);
        });

        Schema::table('penduduks', function (Blueprint $table) {
            $table->foreignId('kartu_keluarga_id')->nullable()->after('id')->constrained('kartu_keluargas')->nullOnDelete();
            $table->string('pendidikan')->nullable()->after('agama');
            $table->string('golongan_darah', 3)->nullable()->after('pendidikan');
            $table->string('shdk')->nullable()->after('golongan_darah');

            $table->index(['nik', 'nama']);
        });
    }

    public function down(): void
    {
        Schema::table('penduduks', function (Blueprint $table) {
            $table->dropForeign(['kartu_keluarga_id']);
            $table->dropIndex(['nik', 'nama']);
            $table->dropColumn([
                'kartu_keluarga_id',
                'pendidikan',
                'golongan_darah',
                'shdk',
            ]);
        });

        Schema::dropIfExists('kartu_keluargas');
    }
};
