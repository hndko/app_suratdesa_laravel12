<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->string('tracking_code', 24)->nullable()->unique()->after('no_surat');
            $table->index(['tracking_code', 'status']);
        });

        DB::table('surats')
            ->whereNull('tracking_code')
            ->orderBy('id')
            ->select('id')
            ->chunkById(100, function ($surats) {
                foreach ($surats as $surat) {
                    DB::table('surats')
                        ->where('id', $surat->id)
                        ->update(['tracking_code' => 'SRT-LEGACY-' . $surat->id]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->dropIndex(['tracking_code', 'status']);
            $table->dropUnique(['tracking_code']);
            $table->dropColumn('tracking_code');
        });
    }
};
