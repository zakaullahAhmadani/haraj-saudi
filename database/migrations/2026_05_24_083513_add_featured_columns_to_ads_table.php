<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            // Add featured columns if they don't exist
            if (!Schema::hasColumn('ads', 'featured_position')) {
                $table->integer('featured_position')->nullable()->after('position');
            }
            if (!Schema::hasColumn('ads', 'featured_until')) {
                $table->timestamp('featured_until')->nullable()->after('featured_position');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn(['featured_position', 'featured_until']);
        });
    }
};