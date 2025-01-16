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
        DB::table('mas_employees')->where('marital_status', '')->update(['marital_status' => 1]);
        Schema::table('mas_employees', function (Blueprint $table) {
            $table->unsignedTinyInteger('marital_status')->comment('1 => Single, 2 => Married, 3 => Divorced')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mas_employees', function (Blueprint $table) {
            $table->string('marital_status', 50)->change();
        });
    }
};
