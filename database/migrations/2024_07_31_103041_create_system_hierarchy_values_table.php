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
        Schema::create('system_hierarchy_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mas_designation_id')->index()->nullable()->connstrained();
            $table->foreignId('mas_department_id')->index()->nullable()->connstrained();
            $table->string('name', 100);
            $table->string('description', 500)->nullable();
            $table->timestamps();
        });

        // add foreign key to system_hierarchies tbl
        schema::table('system_hierarchies', function (Blueprint $table){
            $table->unsignedBigInteger('system_hierarchy_value_id');
            $table->foreign('system_hierarchy_value_id')->references('id')->on('system_hierarchy_values')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the foreign key from system_hierarchies the e table
        Schema::table('system_hierarchies', function (Blueprint $table) {
            $table->dropForeign(['system_hierarchy_value_id']);
            $table->dropColumn('system_hierarchy_value_id');
        });

        Schema::dropIfExists('hierarchy_values');
    }
};
