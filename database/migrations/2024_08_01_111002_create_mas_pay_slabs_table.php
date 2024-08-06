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
        Schema::create('mas_pay_slabs', function (Blueprint $table) {
            $table->id();
            $table->string("name",150)->index();
            $table->date("effective_date");
            $table->text("formula")->nullable();
            $table->uuid("created_by")->index();
            $table->uuid("edited_by")->index()->nullable();
            $table->uuid("updated_by")->index()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mas_pay_slabs');
    }
};
