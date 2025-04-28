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
        // if later on if user wants to have sth where delegation can also be set by HR or admin personnel then there will be slight changes in the approach basically in logic building part
        Schema::create('delegations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delegator_id')->index()->constrained('mas_employees');
            $table->foreignId('delegatee_id')->index()->constrained('mas_employees');
            $table->foreignId('role_id')->index()->consatrained('roles');
            $table->string('module')->index()->nullable()->comment('optional for now if required in future need to make use of this.');
            $table->date('start_date')->index();
            $table->date('end_date')->index();
            $table->text('remark')->nullable();
            //This two are used if incase delegation can be created by HR or admin personnel in future
            $table->boolean('status')->comment('1 => Active, 0 => Inactive');
            $table->foreignId('created_by')->index()->constrained('mas_employees')->comment('created_by user it self is delegator');
            $table->foreignId('updated_by')->index()->nullable()->constrained('mas_employees');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delegations');
    }
};
