<?php

namespace App\Console\Commands\Binance;

use Illuminate\Console\Command;
use function var_dump;

class TestOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'binance:test-order';

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
        $res = \App\Facades\Binance::getRest()->marginNewOrder(
            'ETHUSDT',
            'SELL',
            'MARKET',
            [
                'quantity' => 0.0769,
//                'quoteOrderQty' => 20,
//                'price' => 10,
//                'stopPrice' => 20.01,
//                'timeInForce' => 'GTC',
//                'recvWindow' => 5000
                'sideEffectType' => 'AUTO_REPAY'
            ]
        );

        var_dump($res);
    }
}
