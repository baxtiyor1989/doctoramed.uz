<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointment_applications', function (Blueprint $table) {
            $table->timestamp('viewed_at')->nullable()->after('appointment_type')->index();
        });

        DB::table('appointment_applications')->whereNull('viewed_at')->update(['viewed_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('appointment_applications', function (Blueprint $table) {
            $table->dropColumn('viewed_at');
        });
    }
};
