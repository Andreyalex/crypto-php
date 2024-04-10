<?php

namespace App\Console\Commands\Binance;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use function in_array;
use function intval;
use function strtotime;

class MarketHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'market:history {--asset=BTCUSDT} {--start=} {--end=}';

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
        Log::debug('Fetch asset market history');

        $client = new \App\Robot\Binance\Spot([
            'key' => env('BINANCE_API_KEY'),
            'secret' => env('BINANCE_API_SECRET')
        ]);

        $size = 1000;
        $times = null;
        $asset = $this->option('asset');
        $interval = '1h';
        $options = [
            'limit' => $size
        ];
        $this->option('start') && $options['startTime'] = strtotime($this->option('start')) * 1000;
        $this->option('end') && $options['endTime'] = strtotime($this->option('end')) * 1000;

        $response = $client->klines($asset, $interval, $options);

        foreach ($response as &$row) {
            if ($times === null) {
                $times = \App\Models\Market::where('asset', $asset)->pluck('time')->toArray();
            }
            if (in_array(intval($row[0] / 1000), $times)) continue;
            $model = new \App\Models\Market([
                'asset' => $asset,
                'interval' => $interval,
                'time' => intval($row[0] / 1000),
                'o' => $row[1],
                'h' => $row[2],
                'l' => $row[3],
                'c' => $row[4],
                'volume' => $row[5],
                'quote_volume' => $row[7],
                'buy_base_volume' => $row[9],
                'buy_quote_volume' => $row[10],
            ]);
            $model->save();
        }

        return 0;
    }
}
