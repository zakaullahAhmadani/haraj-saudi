<?php

namespace App\Jobs;

use App\Models\Ad;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class RotateAdsFeed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * How many posts to "promote" per rotation cycle.
     * We take the bottom N posts (oldest last_rotated_at) and stamp them
     * with now(), so they bubble up to the top on the next page load.
     *
     * Setting this to ~20% of total active posts gives a smooth rotation.
     */
    protected int $batchSize;

    public function __construct(int $batchSize = 0)
    {
        $this->batchSize = $batchSize;
    }

    public function handle(): void
    {
        // Exclude admin-pinned posts from rotation
        $pinnedIds = Ad::pinnedByAdmin()->pluck('id')->toArray();

        $totalActive = Ad::active()
            ->when(count($pinnedIds), fn($q) => $q->whereNotIn('id', $pinnedIds))
            ->count();

        if ($totalActive === 0) {
            return;
        }

        // Default batch = ~20% of active posts (min 5, max 50)
        $batch = $this->batchSize > 0
            ? $this->batchSize
            : max(5, min(50, (int) round($totalActive * 0.20)));

        // Grab the IDs of the posts that have been sitting at the top the longest
        // (i.e. those with the OLDEST last_rotated_at, or NULL = never rotated = oldest)
        $idsToRotate = Ad::active()
            ->when(count($pinnedIds), fn($q) => $q->whereNotIn('id', $pinnedIds))
            ->orderByRaw('ISNULL(last_rotated_at) DESC')  // NULLs first
            ->orderBy('last_rotated_at', 'asc')           // then oldest first
            ->limit($batch)
            ->pluck('id')
            ->toArray();

        if (empty($idsToRotate)) {
            return;
        }

        // Stamp them with the current time so they sort to the BOTTOM
        // (feed is sorted last_rotated_at ASC → oldest = top, newest = bottom)
        Ad::whereIn('id', $idsToRotate)
            ->update(['last_rotated_at' => now()]);
    }
}
