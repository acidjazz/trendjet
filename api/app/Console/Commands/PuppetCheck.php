<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BoostService;

class PuppetCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'puppet:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for pending boosts, send any needed puppets';

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
        $bs = new BoostService();
        $this->info("Activated {$bs->activate()} boosts");

        if (count($bs->actives()) < 1) {
            return $this->info('No actives boosts found');
        }

        if ($ids = $bs->deploy()) {
            $this->info("Boosting ids: $ids");
        } else {
            $this->info("Found active instances, no deploy needed");
        }

    }
}
