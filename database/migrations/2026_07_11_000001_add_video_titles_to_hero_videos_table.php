<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_videos', function (Blueprint $table) {
            $table->string('title')->nullable()->change();
            $table->json('video_titles')->nullable()->after('url');
        });
    }

    public function down(): void
    {
        Schema::table('hero_videos', function (Blueprint $table) {
            $table->dropColumn('video_titles');
            $table->string('title')->nullable(false)->change();
        });
    }
};
