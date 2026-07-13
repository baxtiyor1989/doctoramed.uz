<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_slides', function (Blueprint $table) {
            $table->id();
            $table->json('translations')->nullable();
            $table->string('tag')->nullable();
            $table->string('title');
            $table->text('text')->nullable();
            $table->text('items')->nullable();
            $table->text('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_slides');
    }
};
