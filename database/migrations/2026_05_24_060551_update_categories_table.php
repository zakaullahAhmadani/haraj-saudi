<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Add arabic_name column if it doesn't exist
            if (!Schema::hasColumn('categories', 'arabic_name')) {
                $table->string('arabic_name')->nullable()->after('name');
            }
            
            // Add parent_id column if it doesn't exist
            if (!Schema::hasColumn('categories', 'parent_id')) {
                $table->foreignId('parent_id')->nullable()->after('order')->constrained('categories')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['arabic_name', 'parent_id']);
        });
    }
};