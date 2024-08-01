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
        Schema::create('pay_groups', function (Blueprint $table) {
            $table->id();
            $table->string("name",150)->index();
            $table->tinyInteger("applicable_on")->comment("1 for Employee Group, 2 for Grade");
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
        Schema::dropIfExists('pay_groups');
    }
};
