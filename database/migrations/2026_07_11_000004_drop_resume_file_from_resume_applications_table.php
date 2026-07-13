<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resume_applications', function (Blueprint $table) {
            $table->dropColumn('resume_file');
        });
    }

    public function down(): void
    {
        Schema::table('resume_applications', function (Blueprint $table) {
            $table->string('resume_file')->nullable()->after('message');
        });
    }
};
