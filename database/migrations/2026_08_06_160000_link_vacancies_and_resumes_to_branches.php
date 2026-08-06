<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vacancies', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        Schema::table('resume_applications', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('address')->constrained()->nullOnDelete();
            $table->foreignId('vacancy_id')->nullable()->after('branch_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('resume_applications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('vacancy_id');
            $table->dropConstrainedForeignId('branch_id');
        });
        Schema::table('vacancies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('branch_id');
        });
    }
};
