<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            // Add is_pinned column
            if (!Schema::hasColumn('ads', 'is_pinned')) {
                $table->boolean('is_pinned')->default(false)->after('is_active');
            }
            
            // Add pinned_at column
            if (!Schema::hasColumn('ads', 'pinned_at')) {
                $table->timestamp('pinned_at')->nullable()->after('is_pinned');
            }
            
            // Add other missing columns if needed
            if (!Schema::hasColumn('ads', 'last_rotated_at')) {
                $table->timestamp('last_rotated_at')->nullable()->after('views');
            }
            
            if (!Schema::hasColumn('ads', 'last_boosted_at')) {
                $table->timestamp('last_boosted_at')->nullable()->after('last_rotated_at');
            }
            
            if (!Schema::hasColumn('ads', 'hourly_views')) {
                $table->integer('hourly_views')->default(0)->after('last_boosted_at');
            }
            
            if (!Schema::hasColumn('ads', 'last_hour_views')) {
                $table->integer('last_hour_views')->default(0)->after('hourly_views');
            }
            
            if (!Schema::hasColumn('ads', 'position')) {
                $table->integer('position')->nullable()->after('last_hour_views');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn([
                'is_pinned',
                'pinned_at',
                'last_rotated_at',
                'last_boosted_at',
                'hourly_views',
                'last_hour_views',
                'position'
            ]);
        });
    }
};