<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->string('category')->nullable()->after('experience');
            $table->text('education')->nullable()->after('category');
            $table->string('work_schedule')->nullable()->after('education');
            $table->longText('bio')->nullable()->after('work_schedule');
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn(['category', 'education', 'work_schedule', 'bio']);
        });
    }
};
