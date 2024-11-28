<?php

namespace App\Console\Commands;

use App\Jobs\ProcessGameFetch;
use App\Models\Game;
use Illuminate\Console\Command;

class QueueGameGetter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:queue-game-getter {--from=} {--to=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will dispatch Game Getter Jobs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $from = $this->option('from');
        $to = $this->option('to');

        if (!is_numeric($from) || !is_numeric($to)) {
            $this->error('From and To are required and have to be numeric!');
            return;
        }
        if ($from >= $to) {
            $this->error('From must be less than To!');
            return;
        }
        if ($from == 0) {
            $this->error('From must be greater than zero!');
            return;
        }

        $games = Game::where('bgg_thing_id', '>=', $from)->where('bgg_thing_id', '<=', $to)->get();
        while($from <= $to) {
            $game = $games->where('bgg_thing_id', $from)->first();
            if ($game instanceof Game) {
                $this->line($from.' Game already in DB');
                $from++;
                continue;
            }
            $this->line($from.' Dispatching game to be fetched');
            dispatch(new ProcessGameFetch($from));
            $from++;
        }
    }
}
