<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_counters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surats')->cascadeOnDelete();
            $table->unsignedSmallInteger('tahun');
            $table->unsignedInteger('next_number')->default(1);
            $table->timestamps();

            $table->unique(['jenis_surat_id', 'tahun']);
        });

        Schema::table('surats', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreignId('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['tanggal_surat', 'status']);
            $table->index(['jenis_surat_id', 'tanggal_surat']);
            $table->index(['penduduk_id', 'tanggal_surat']);
        });

        Schema::table('pengaduans', function (Blueprint $table) {
            $table->index(['status', 'created_at']);
            $table->index('created_at');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->index(['status', 'created_at']);
        });

        Schema::table('penduduks', function (Blueprint $table) {
            $table->index('nama');
        });
    }

    public function down(): void
    {
        Schema::table('penduduks', function (Blueprint $table) {
            $table->dropIndex(['nama']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
        });

        Schema::table('pengaduans', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('surats', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['tanggal_surat', 'status']);
            $table->dropIndex(['jenis_surat_id', 'tanggal_surat']);
            $table->dropIndex(['penduduk_id', 'tanggal_surat']);
            $table->foreignId('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::dropIfExists('surat_counters');
    }
};
