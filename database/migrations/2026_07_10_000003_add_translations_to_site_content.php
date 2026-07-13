<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['site_settings', 'services', 'doctors', 'testimonials', 'articles', 'partners'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->json('translations')->nullable()->after('id');
            });
        }
    }

    public function down(): void
    {
        foreach (['site_settings', 'services', 'doctors', 'testimonials', 'articles', 'partners'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('translations');
            });
        }
    }
};
