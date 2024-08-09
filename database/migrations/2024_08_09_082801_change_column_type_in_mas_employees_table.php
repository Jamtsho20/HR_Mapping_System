<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('mas_employees')->where('gender', '')->update(['gender' => 1]);

        Schema::table('mas_employees', function (Blueprint $table) {
            $table->unsignedTinyInteger('gender')->comment('1 => Male, 2 => Female, 3 => Other')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mas_employees', function (Blueprint $table) {
            $table->string('gender', 30)->change();
        });
    }
};
