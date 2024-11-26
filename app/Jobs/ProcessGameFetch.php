<?php

namespace App\Jobs;

use App\Services\GameService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;

class ProcessGameFetch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $bggId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $bggId)
    {
        $this->bggId = $bggId;
        $this->queue = 'bgg-call';
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        GameService::getGame($this->bggId);
    }

    public function middleware(): array {
        return [(new RateLimited('bgg-call'))];
    }

    public function tries(): int {
        return 20;
    }
}
