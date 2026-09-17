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
        // Add columns to track certificate creation and handover
        if (Schema::hasTable('tbl_sertifikat_pkl')) {
            Schema::table('tbl_sertifikat_pkl', function (Blueprint $table) {
                // Track who created the certificate
                if (!Schema::hasColumn('tbl_sertifikat_pkl', 'created_by_role')) {
                    $table->enum('created_by_role', ['mentor', 'sekretariat'])->nullable()->after('created_id')
                          ->comment('Role yang membuat sertifikat');
                }
                
                // Track certificate handover status
                if (!Schema::hasColumn('tbl_sertifikat_pkl', 'sertifikat_status')) {
                    $table->enum('sertifikat_status', ['draft', 'ready_for_handover', 'handed_over', 'printed'])
                          ->default('draft')->after('created_by_role')
                          ->comment('Status sertifikat dalam workflow');
                }
                
                // Mentor who receives handover from Sekretariat
                if (!Schema::hasColumn('tbl_sertifikat_pkl', 'handed_to_mentor_id')) {
                    $table->unsignedBigInteger('handed_to_mentor_id')->nullable()->after('sertifikat_status')
                          ->comment('ID Mentor yang menerima sertifikat dari Sekretariat');
                }
                
                // Timestamp for handover
                if (!Schema::hasColumn('tbl_sertifikat_pkl', 'handed_over_at')) {
                    $table->timestamp('handed_over_at')->nullable()->after('handed_to_mentor_id')
                          ->comment('Waktu sertifikat diserahkan ke Mentor');
                }
            });
        }

        // Create table for certificate handover audit trail
        if (!Schema::hasTable('sertifikat_handovers')) {
            Schema::create('sertifikat_handovers', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('id_sertifikat');
                $table->bigInteger('from_user_id'); // Sekretariat - change to bigInteger to match
                $table->bigInteger('to_user_id');   // Mentor - change to bigInteger to match
                $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
                $table->text('notes')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('accepted_at')->nullable();

                $table->foreign('id_sertifikat')
                      ->references('id_sertifikat')
                      ->on('tbl_sertifikat_pkl')
                      ->onDelete('cascade');

                $table->foreign('from_user_id')
                      ->references('id')
                      ->on('tbl_login')
                      ->onDelete('cascade');

                $table->foreign('to_user_id')
                      ->references('id')
                      ->on('tbl_login')
                      ->onDelete('cascade');

                $table->index(['from_user_id', 'to_user_id']);
                $table->index(['status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikat_handovers');

        if (Schema::hasTable('tbl_sertifikat_pkl')) {
            Schema::table('tbl_sertifikat_pkl', function (Blueprint $table) {
                if (Schema::hasColumn('tbl_sertifikat_pkl', 'handed_over_at')) {
                    $table->dropColumn('handed_over_at');
                }
                if (Schema::hasColumn('tbl_sertifikat_pkl', 'handed_to_mentor_id')) {
                    $table->dropColumn('handed_to_mentor_id');
                }
                if (Schema::hasColumn('tbl_sertifikat_pkl', 'sertifikat_status')) {
                    $table->dropColumn('sertifikat_status');
                }
                if (Schema::hasColumn('tbl_sertifikat_pkl', 'created_by_role')) {
                    $table->dropColumn('created_by_role');
                }
            });
        }
    }
};
