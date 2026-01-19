<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat')->unique();
            $table->foreignId('penduduk_id')->constrained();
            $table->foreignId('jenis_surat_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->date('tanggal_surat');
            $table->text('keperluan')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('file_arsip')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
