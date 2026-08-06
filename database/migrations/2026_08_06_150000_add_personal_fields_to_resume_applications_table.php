<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resume_applications', function (Blueprint $table) {
            $table->string('last_name')->nullable()->after('id');
            $table->string('first_name')->nullable()->after('last_name');
            $table->date('birth_date')->nullable()->after('full_name');
            $table->foreignId('region_id')->nullable()->after('birth_date')->constrained()->nullOnDelete();
            $table->foreignId('district_id')->nullable()->after('region_id')->constrained()->nullOnDelete();
            $table->string('region_district')->nullable()->after('district_id');
            $table->string('address')->nullable()->after('region_district');
        });
    }

    public function down(): void
    {
        Schema::table('resume_applications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('district_id');
            $table->dropConstrainedForeignId('region_id');
            $table->dropColumn(['last_name', 'first_name', 'birth_date', 'region_district', 'address']);
        });
    }
};
