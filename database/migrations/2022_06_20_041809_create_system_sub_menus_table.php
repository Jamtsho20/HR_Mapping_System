<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_sub_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_menu_id')->index()->constrained();
            $table->string('name');
            $table->string('route');
            $table->unsignedInteger('display_order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_sub_menus');
    }
};
