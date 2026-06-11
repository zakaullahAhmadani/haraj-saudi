<?php

namespace App\Console\Commands;

use App\Models\Ad;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RotatePostsByViews extends Command
{
    protected $signature = 'posts:rotate';
    protected $description = 'Rotate posts based on views every hour - lowest views go to top';

    public function handle()
    {
        // Get current timestamp
        $now = now();
        $oneHourAgo = $now->copy()->subHour();

        // Update hourly_views for all active posts
        Ad::where('is_active', true)
            ->where('status', 'approved')
            ->update([
                'hourly_views' => DB::raw('views - COALESCE(last_hour_views, 0)'),
                'last_hour_views' => DB::raw('views'),
                'last_boosted_at' => $now
            ]);

        // Get posts ordered by views (lowest views first)
        $posts = Ad::where('is_active', true)
            ->where('status', 'approved')
            ->orderBy('hourly_views', 'asc')  // Lowest views go to top
            ->orderBy('created_at', 'desc')
            ->get();

        // Update order (you can add a 'position' column if needed)
        foreach ($posts as $index => $post) {
            $post->position = $index + 1;
            $post->save();
        }

        $this->info('Posts rotated successfully. ' . $posts->count() . ' posts updated.');
    }
}