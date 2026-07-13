<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->text('excerpt')->nullable()->after('title');
            $table->longText('body')->nullable()->after('excerpt');
            $table->json('gallery_images')->nullable()->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['excerpt', 'body', 'gallery_images']);
        });
    }
};
