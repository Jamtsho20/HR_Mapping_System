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
        Schema::create('sifa_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sifa_registration_id')->index()->constrained('sifa_registrations')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('family_tree')->comment('Certified family tree of the member');
            $table->string('cid_of_dep_nom')->comment('Copies of Citizen Identity Card(s) of dependent(s) and nominee(s) of the member');
            $table->string('marriage_certificate')->nullable->comment('Marriage Certificate / Confirmation of Marriage, if married');
            $table->string('family_tree_spouse')->nullable->comment('Certified family tree of spouse of the member, if married');
            $table->string('spouse_cid')->nullable->comment('Copies of Citizenship Identity Cards of the dependent(s) on the spouse side, if married');
            $table->string('birth_certificate')->nullable->comment('Birth Certificate(s) of biological children, if married and have children');
            $table->string('adopted_children')->nullable->comment('Legal documents in case of foster parents and adopted children of the member');
            $table->string('if_divorced')->nullable->comment('If divorced, court verdict / legal agreement endorsed by a Royal Court of Justice');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sifa_documents');
    }
};
