<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Register your custom commands here
        \App\Console\Commands\SubmitSitemapToSearchEngines::class,
        \App\Console\Commands\GenerateSitemap::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Submit sitemap to search engines weekly (every Monday at 1 AM)
        $schedule->command('seo:submit-sitemap')->weekly()->mondays()->at('01:00');
        
        // Clear sitemap cache daily at midnight to regenerate
        $schedule->call(function () {
            \Cache::forget('sitemap');
            \Cache::forget('sitemap_index');
        })->dailyAt('00:00');
        
        // Generate sitemap daily at 1 AM
        $schedule->command('sitemap:generate')->dailyAt('01:00');
        
        // Rotate posts every hour (your existing rotation)
        $schedule->command('posts:rotate')->hourly();
        
        // Clean old sessions weekly
        $schedule->command('session:gc')->weekly();
        
        // Optimize database daily
        $schedule->command('model:prune')->daily();
        
        // Backup database daily (optional)
        // $schedule->command('backup:run')->daily()->at('02:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}