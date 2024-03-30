<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use function env;
use function json_encode;

class AccountInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:info';

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
        $client = new \Binance\Spot();
        $response = $client->time();
        echo json_encode($response);

        $client = new \Binance\Spot([
            'key' => env('BINANCE_API_KEY'),
            'secret' => env('BINANCE_API_SECRET')
        ]);
        $response = $client->account();
        echo json_encode($response);

        return 0;
    }
}
