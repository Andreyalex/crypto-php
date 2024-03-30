<?php

namespace App\Console\Commands;

use App\Robot\EarnDaemon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class EarnApr extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'earn-apr:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        Log::debug('Earn apr puller started');

        $daemon = new EarnDaemon($this->output);
        $daemon->start();
        return 0;
    }
}
