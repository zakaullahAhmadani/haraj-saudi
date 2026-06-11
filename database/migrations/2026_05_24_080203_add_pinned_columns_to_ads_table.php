<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('ads', 'is_pinned')) {
                $table->boolean('is_pinned')->default(false)->after('is_active');
            }
            
            if (!Schema::hasColumn('ads', 'pinned_at')) {
                $table->timestamp('pinned_at')->nullable()->after('is_pinned');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn(['is_pinned', 'pinned_at']);
        });
    }
};