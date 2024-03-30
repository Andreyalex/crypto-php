<?php

namespace App\Console\Commands;

use App\Robot\Daemon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TradeStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trader:start';

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
        Log::debug('Trader started');

        $daemon = new Daemon($this->output);
        $daemon->start();
        return 0;
    }
}
