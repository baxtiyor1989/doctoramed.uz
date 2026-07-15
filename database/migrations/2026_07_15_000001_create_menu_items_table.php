<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->nullOnDelete();
            $table->string('title');
            $table->string('url');
            $table->json('translations')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $items = [
            ['title' => 'Bosh sahifa', 'url' => '#home', 'sort_order' => 10, 'translations' => ['title' => ['uz' => 'Bosh sahifa', 'ru' => 'Главная', 'en' => 'Home']]],
            ['title' => 'Xizmatlar', 'url' => '#services', 'sort_order' => 20, 'translations' => ['title' => ['uz' => 'Xizmatlar', 'ru' => 'Услуги', 'en' => 'Services']]],
            ['title' => 'Shifokorlar', 'url' => '#doctors', 'sort_order' => 30, 'translations' => ['title' => ['uz' => 'Shifokorlar', 'ru' => 'Врачи', 'en' => 'Doctors']]],
            ['title' => 'Klinika haqida', 'url' => '#about', 'sort_order' => 40, 'translations' => ['title' => ['uz' => 'Klinika haqida', 'ru' => 'О клинике', 'en' => 'About clinic']]],
            ['title' => 'Yangiliklar', 'url' => '/news', 'sort_order' => 50, 'translations' => ['title' => ['uz' => 'Yangiliklar', 'ru' => 'Новости', 'en' => 'News']]],
            ['title' => 'Aloqa', 'url' => '#contact', 'sort_order' => 60, 'translations' => ['title' => ['uz' => 'Aloqa', 'ru' => 'Контакты', 'en' => 'Contact']]],
        ];

        foreach ($items as $item) {
            DB::table('menu_items')->insert([
                ...$item,
                'translations' => json_encode($item['translations'], JSON_UNESCAPED_UNICODE),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
