<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PuppetService;

// use Aws\Ec2\Ec2Client;

class SendPuppet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:puppet {boost_ids}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a puppet to watch a video, then report back';

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
        $puppet = new PuppetService(explode(',', $this->argument('boost_ids')));
        $puppet->deploy();
        $this->info('Puppet instance launched successfully');
    }
}
