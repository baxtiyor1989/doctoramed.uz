<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('facebook_url')->nullable()->after('website');
            $table->string('telegram_url')->nullable()->after('facebook_url');
            $table->string('instagram_url')->nullable()->after('telegram_url');
            $table->string('youtube_url')->nullable()->after('instagram_url');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['facebook_url', 'telegram_url', 'instagram_url', 'youtube_url']);
        });
    }
};
