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
        Schema::create('asset_commission_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->index()->constrained('mas_comission_types');
            $table->string('commission_no')->index();
            // $table->foreignId('goods_received_by_user_id')->index()->constrained('mas_goods_received_by_users');
            $table->foreignId('goods_received_detail_id')->index()->constrained('goods_received_details')->comment('do commission against goods received detail as it has to be done against each GRN');
            $table->date('commission_date')->index()->comment('combination of code and grn_no');
            $table->integer('received_quantity');
            $table->integer('comissioned_quantity');
            $table->json('file')->nullable();
            // $table->timestamp()
            $table->unsignedTinyInteger('status')->comment('1 => New, 2 =>verified 3 => Approved');
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
        Schema::dropIfExists('asset_commission_applications');
    }
};
