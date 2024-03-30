<?php

namespace App\Console\Commands\Binance;

use App\Robot\Actions\OpenShortPosition;
use Illuminate\Console\Command;
use function var_dump;

class TestPosition extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'binance:test-position';

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
        $action = new OpenShortPosition();
        $order = $action();

        var_dump($order);
    }
}
