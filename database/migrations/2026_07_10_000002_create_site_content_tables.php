<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_title');
            $table->string('brand_name');
            $table->string('brand_subtitle')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('website')->nullable();
            $table->string('hero_eyebrow')->nullable();
            $table->string('hero_title')->nullable();
            $table->string('hero_highlight')->nullable();
            $table->text('hero_text')->nullable();
            $table->json('hero_features')->nullable();
            $table->json('stats')->nullable();
            $table->string('services_subtitle')->nullable();
            $table->string('services_title')->nullable();
            $table->text('services_text')->nullable();
            $table->string('about_tag')->nullable();
            $table->string('about_title')->nullable();
            $table->text('about_text')->nullable();
            $table->text('about_image')->nullable();
            $table->json('about_items')->nullable();
            $table->string('doctors_subtitle')->nullable();
            $table->string('doctors_title')->nullable();
            $table->string('testimonials_subtitle')->nullable();
            $table->string('testimonials_title')->nullable();
            $table->string('appointment_title')->nullable();
            $table->text('appointment_text')->nullable();
            $table->text('appointment_image')->nullable();
            $table->string('appointment_hours')->nullable();
            $table->string('news_subtitle')->nullable();
            $table->string('news_title')->nullable();
            $table->text('footer_text')->nullable();
            $table->string('footer_copyright')->nullable();
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('specialty')->nullable();
            $table->string('experience')->nullable();
            $table->text('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role')->nullable();
            $table->text('text');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('image')->nullable();
            $table->date('published_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('services');
        Schema::dropIfExists('site_settings');
    }
};
