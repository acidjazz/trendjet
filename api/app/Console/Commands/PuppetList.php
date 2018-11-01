<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PuppetService;

class PuppetList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'puppet:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show any existing puppets doing work';

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
        $puppet = new PuppetService();
        if ($instances = $puppet->describe()) {
            $headers = ['InstanceId', 'State'];
            return $this->table($headers, $instances);
        }
        return $this->info('No active instances found');
    }
}
