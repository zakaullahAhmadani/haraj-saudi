<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            // When this post is pinned to top, until what datetime
            $table->timestamp('featured_until')->nullable()->after('is_featured');
            // Position 1-5 for pinned posts (null = not pinned by admin)
            $table->unsignedTinyInteger('featured_position')->nullable()->after('featured_until');

            $table->index('featured_until');
            $table->index('featured_position');
        });
    }

    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn(['featured_until', 'featured_position']);
        });
    }
};
