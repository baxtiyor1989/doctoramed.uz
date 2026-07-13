<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->json('translations')->nullable();
            $table->string('title');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('resume_applications', function (Blueprint $table) {
            $table->string('branch')->nullable()->after('position');
        });
    }

    public function down(): void
    {
        Schema::table('resume_applications', function (Blueprint $table) {
            $table->dropColumn('branch');
        });

        Schema::dropIfExists('branches');
    }
};
