<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointment_applications', function (Blueprint $table) {
            $table->string('last_name')->nullable()->after('id');
            $table->string('first_name')->nullable()->after('last_name');
        });
    }

    public function down(): void
    {
        Schema::table('appointment_applications', function (Blueprint $table) {
            $table->dropColumn(['last_name', 'first_name']);
        });
    }
};
