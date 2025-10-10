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
        Schema::create('training_applications', function (Blueprint $table) {
            $table->id();
            //At times employee need to upload their training those done self funded at times training_list_id will be null and do not require to follow hierarchy
            $table->foreignId('training_list_id')->nullable()->constrained('mas_training_lists')->cascadeOnUpdate()->restrictOnDelete();
            // $table->foreignId('type_id')->constrained('mas_training_types')->cascadeOnUpdate()->restrictOnDelete();
            $table->boolean('is_self_funded')->default(0);
            $table->tinyInteger('status')->default(1)->comment('-1 => Rejected, 0 => cancelled/withdrawn, 1 => New, 2 => Approved');
            $table->foreignId('created_by')->index()->constrained('mas_employees');
            $table->foreignId('updated_by')->index()->nullable()->constrained('mas_employees');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_applications');
    }
};
