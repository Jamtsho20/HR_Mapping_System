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
        Schema::create('acc_account_heads', function (Blueprint $table) {
            $table->id();
            $table->string("code",30)->index();
            $table->string("name",100)->index();
            $table->tinyInteger("type")->comment("1 for Credit, 2 for Debit");
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
        Schema::dropIfExists('acc_account_heads');
    }
};
