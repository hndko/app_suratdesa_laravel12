<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('provider_type');
            $table->string('base_url')->nullable();
            $table->text('api_key')->nullable();
            $table->string('model');
            $table->decimal('temperature', 3, 2)->default(0.30);
            $table->unsignedInteger('max_tokens')->default(800);
            $table->unsignedInteger('timeout')->default(20);
            $table->unsignedTinyInteger('retry')->default(1);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_fallback')->default(false);
            $table->timestamps();

            $table->index(['provider_type', 'is_active']);
            $table->index('is_fallback');
        });

        Schema::create('ai_usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_provider_id')->nullable()->constrained('ai_providers')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('feature');
            $table->string('model')->nullable();
            $table->string('status')->default('success');
            $table->unsignedInteger('prompt_tokens')->nullable();
            $table->unsignedInteger('completion_tokens')->nullable();
            $table->unsignedInteger('total_tokens')->nullable();
            $table->unsignedInteger('latency_ms')->nullable();
            $table->string('error_code')->nullable();
            $table->text('error_message')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['feature', 'status']);
            $table->index('created_at');
        });

        Schema::create('ai_prompt_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('system_prompt');
            $table->text('user_prompt_template')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('pengaduan_ai_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengaduan_id')->constrained('pengaduans')->cascadeOnDelete();
            $table->foreignId('ai_provider_id')->nullable()->constrained('ai_providers')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('summary')->nullable();
            $table->string('recommended_category')->nullable();
            $table->string('priority')->nullable();
            $table->text('draft_reply')->nullable();
            $table->json('raw_response')->nullable();
            $table->timestamps();

            $table->index(['pengaduan_id', 'created_at']);
        });

        Schema::create('surat_ai_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->nullable()->constrained('jenis_surats')->nullOnDelete();
            $table->foreignId('surat_id')->nullable()->constrained('surats')->cascadeOnDelete();
            $table->foreignId('ai_provider_id')->nullable()->constrained('ai_providers')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('suggestion_type');
            $table->text('original_text')->nullable();
            $table->text('suggested_text')->nullable();
            $table->json('placeholder_report')->nullable();
            $table->json('raw_response')->nullable();
            $table->timestamps();

            $table->index(['suggestion_type', 'created_at']);
        });

        Schema::create('surat_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained('surats')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['surat_id', 'created_at']);
        });

        Schema::create('surat_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id')->constrained('surats')->cascadeOnDelete();
            $table->string('verification_code', 32)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index(['verification_code', 'is_active']);
        });

        Schema::create('import_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type');
            $table->string('file_name');
            $table->string('status')->default('preview');
            $table->unsignedInteger('total_rows')->default(0);
            $table->unsignedInteger('valid_rows')->default(0);
            $table->unsignedInteger('invalid_rows')->default(0);
            $table->unsignedInteger('processed_rows')->default(0);
            $table->timestamps();

            $table->index(['type', 'status']);
        });

        Schema::create('import_batch_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_batch_id')->constrained('import_batches')->cascadeOnDelete();
            $table->unsignedInteger('row_number');
            $table->json('payload');
            $table->json('errors')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->index(['import_batch_id', 'status']);
        });

        Schema::table('surats', function (Blueprint $table) {
            $table->timestamp('verified_at')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('verified_at');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
            $table->text('approval_note')->nullable()->after('rejected_at');
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE surats MODIFY status ENUM('pending','process','verified','approved','done','rejected') DEFAULT 'pending'");
        }

        DB::table('surats')
            ->where('status', 'done')
            ->orderBy('id')
            ->select('id')
            ->chunkById(100, function ($surats) {
                foreach ($surats as $surat) {
                    do {
                        $code = 'VRF-' . strtoupper(Str::random(12));
                    } while (DB::table('surat_verifications')->where('verification_code', $code)->exists());

                    DB::table('surat_verifications')->insert([
                        'surat_id' => $surat->id,
                        'verification_code' => $code,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->dropColumn(['verified_at', 'approved_at', 'rejected_at', 'approval_note']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::table('surats')->whereIn('status', ['verified', 'approved'])->update(['status' => 'process']);
            DB::table('surats')->where('status', 'rejected')->update(['status' => 'pending']);
            DB::statement("ALTER TABLE surats MODIFY status ENUM('pending','process','done') DEFAULT 'pending'");
        }

        Schema::dropIfExists('import_batch_rows');
        Schema::dropIfExists('import_batches');
        Schema::dropIfExists('surat_verifications');
        Schema::dropIfExists('surat_approvals');
        Schema::dropIfExists('surat_ai_suggestions');
        Schema::dropIfExists('pengaduan_ai_suggestions');
        Schema::dropIfExists('ai_prompt_templates');
        Schema::dropIfExists('ai_usage_logs');
        Schema::dropIfExists('ai_providers');
    }
};
