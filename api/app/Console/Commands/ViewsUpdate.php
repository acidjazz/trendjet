<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Services\VideoService;

class ViewsUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'views:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the view counts for any videos with curren boosts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $vs = new VideoService;
        $boosts = $vs->currentBoosts();

        if ($boosts->count() < 1) {
            $this->info('No boosts younger than 24hrs found');
            return true;
        }

        $views = $vs->getViews($boosts);
        $vs->updateViews($boosts, $views);

        return $this->info('Updated '.$boosts->count().' videos');
    }
}
