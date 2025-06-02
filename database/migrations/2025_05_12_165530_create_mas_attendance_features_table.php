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
        Schema::create('mas_attendance_features', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Feature name like Check-in, Check-out, Geofencing, QR Code Scan, Approval Process');
            $table->text('description')->nullable()->comment('Description/Explanation for each features');
            $table->boolean('is_mandatory')->default(false)->comment('Is this feature mandatory?');
            $table->boolean('status')->default(true)->comment('1 => Active, 0 => Inactive');
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
        Schema::dropIfExists('mas_attendance_features');
    }
};
