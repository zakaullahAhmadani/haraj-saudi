<?php

use App\Jobs\RotateAdsFeed;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── Rotate the home-page feed every hour ──────────────────────────────────
// Takes the posts that have been sitting at the top the longest and moves
// them to the bottom, so every post gets equal visibility over time.
Schedule::job(new RotateAdsFeed())->hourly()->name('rotate-ads-feed')->withoutOverlapping();

// Also provide an artisan command to trigger manually / test
Artisan::command('ads:rotate {--batch=0 : Number of posts to rotate (0 = auto 20%)}', function () {
    $batch = (int) $this->option('batch');
    RotateAdsFeed::dispatchSync($batch);
    $this->info('Feed rotated successfully.');
})->purpose('Rotate the home-page ads feed (bottom posts come to top)');
