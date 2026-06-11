<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            if (!Schema::hasColumn('ads', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved')->after('is_active');
            }
            if (!Schema::hasColumn('ads', 'is_pinned')) {
                $table->boolean('is_pinned')->default(false)->after('status');
            }
            if (!Schema::hasColumn('ads', 'pinned_at')) {
                $table->timestamp('pinned_at')->nullable()->after('is_pinned');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn(['status', 'is_pinned', 'pinned_at']);
        });
    }
};