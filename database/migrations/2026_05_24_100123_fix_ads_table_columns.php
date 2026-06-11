<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            // Add status column if not exists
            if (!Schema::hasColumn('ads', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved')->after('is_active');
            }
            
            // Add slug column if not exists
            if (!Schema::hasColumn('ads', 'slug')) {
                $table->string('slug')->unique()->nullable()->after('title');
            }
            
            // Add is_pinned column if not exists
            if (!Schema::hasColumn('ads', 'is_pinned')) {
                $table->boolean('is_pinned')->default(false)->after('status');
            }
            
            // Add pinned_at column if not exists
            if (!Schema::hasColumn('ads', 'pinned_at')) {
                $table->timestamp('pinned_at')->nullable()->after('is_pinned');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn(['status', 'slug', 'is_pinned', 'pinned_at']);
        });
    }
};