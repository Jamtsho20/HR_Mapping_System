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
        Schema::create('application_histories', function (Blueprint $table) {
            $table->id();
            $table->morphs('application');
            $table->unsignedTinyInteger('approval_option')->comment('1 => Hierarchical, 2 => single user, 3 => auto approval');
            $table->foreignId('hierarchy_id')->index()->nullable()->constrained('system_hierarchies')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('level_id')->index()->nullable()->constrained('system_hierarchy_levels')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('approver_role_id')->index()->nullable()->constrained('roles')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('approver_emp_id')->index()->nullable()->constrained('mas_employees')->restrictOnDelete()->cascadeOnUpdate();
            $table->unsignedInteger('level_sequence')->nullable();
            $table->tinyInteger('status')->comment('-1 => Rejected, 0 => cancelled/withdrawn, 1 => New, 2 => Verified, 3 => Approved');
            $table->text('remarks')->nullable();
            $table->foreignId('action_performed_by')->index()->constrained('mas_employees')->restrictOnDelete()->cascadeOnUpdate();
            // $table->foreignId('approved_by')->nullable()->index()->constrained('mas_employees')->cascadeOnDelete()->cascadeOnUpdate();
            // $table->foreignId('rejected_by')->nullable()->index()->constrained('mas_employees')->cascadeOnDelete()->cascadeOnUpdate();
            // $table->foreignId('cancelled_by')->nullable()->index()->constrained('mas_employees')->cascadeOnDelete()->cascadeOnUpdate();
            // $table->foreignId('updated_by')->nullable()->index()->constrained('mas_employees')->cascadeOnDelete()->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_histories');
    }
};
