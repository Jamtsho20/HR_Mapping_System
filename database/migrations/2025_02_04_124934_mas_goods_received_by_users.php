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
        Schema::create('mas_goods_received_by_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requistion_application_id')->index()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->integer('received_quantity');
            $table->foreignId('received_from')->index()->constrained('mas_employees');
            $table->foreignId('received_by')->index()->constrained('mas_employees');
            $table->timestamp('received_at')->default(\DB::raw('CURRENT_TIMESTAMP'))->comment('Timestamp when goods were received by the user');
            $table->boolean('is_confirmed')->default(0)->comment('1 = Confirmed by user, 0 = Pending confirmation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mas_goods_received_by_users');
    }
};
