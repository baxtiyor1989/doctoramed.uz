<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->json('translations')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->json('translations')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('appointment_applications', function (Blueprint $table) {
            $table->foreignId('region_id')->nullable()->after('birth_date')->constrained()->nullOnDelete();
            $table->foreignId('district_id')->nullable()->after('region_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('appointment_applications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('district_id');
            $table->dropConstrainedForeignId('region_id');
        });
        Schema::dropIfExists('districts');
        Schema::dropIfExists('regions');
    }
};
