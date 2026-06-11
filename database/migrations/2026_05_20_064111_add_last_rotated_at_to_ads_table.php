<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            // Tracks when this post was last "rotated" to the top of the feed.
            // Sorting ASC by this column = oldest rotation comes first (top of feed).
            $table->timestamp('last_rotated_at')->nullable()->after('featured_position');
            $table->index('last_rotated_at');
        });
    }

    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropIndex(['last_rotated_at']);
            $table->dropColumn('last_rotated_at');
        });
    }
};
