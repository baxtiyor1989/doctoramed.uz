<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointment_applications', function (Blueprint $table) {
            $table->string('address')->nullable()->after('region_district');
        });
    }

    public function down(): void
    {
        Schema::table('appointment_applications', function (Blueprint $table) {
            $table->dropColumn('address');
        });
    }
};
