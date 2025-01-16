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
        Schema::create('application_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('application_type')->index();
            $table->unsignedBigInteger('application_id')->index();
            $table->unsignedTinyInteger('approval_option')->comment('1 => Hierarchical, 2 => single user, 3 => auto approval');
            $table->foreignId('hierarchy_id')->index()->nullable()->constrained('system_hierarchies')->restrictOnDelete()->cascadeOnUpdate();
            $table->tinyInteger('status')->comment('-1 => Rejected, 0 => cancelled/withdrawn, 1 => New, 2 => Verified, 3 => Approved');
            $table->text('remarks')->nullable();
            $table->foreignId('action_performed_by')->index()->nullable()->constrained('mas_employees')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('edited_by')->index()->nullable()->constrained('mas_employees')->restrictOnDelete()->cascadeOnUpdate();
            $table->json('sap_response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_audit_logs');
    }
};
