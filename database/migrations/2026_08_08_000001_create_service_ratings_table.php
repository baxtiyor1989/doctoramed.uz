<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('score');
            $table->string('voter_hash', 64)->unique();
            $table->string('locale', 5)->default('uz');
            $table->timestamps();
            $table->index('score');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_ratings');
    }
};
