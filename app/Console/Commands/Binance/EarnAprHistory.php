<?php

namespace App\Console\Commands\Binance;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use function in_array;
use function intval;
use function strtotime;

class EarnAprHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'earn-apr:history {--productId=USDT001} {--start=} {--end=}';

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
        Log::debug('Fetch Earn apr history');

        $client = new \App\Robot\Binance\Spot([
            'key' => env('BINANCE_API_KEY'),
            'secret' => env('BINANCE_API_SECRET')
        ]);

        $size = 100;
        $current = 0;
        $total = null;
        $times = null;

        do {
            $current++;

            $response = $client->earnFlexibleRateHistory([
                'productId' => $this->option('productId'),
                'startTime' => $this->option('start') ? strtotime($this->option('start')) * 1000 : null,
                'endTime' => $this->option('end') ? strtotime($this->option('end')) * 1000 : null,
                'size' => $size,
                'current' => $current
            ]);

            if (!array_key_exists('rows', $response)) {
                break;
            }

            if ($total === null) $total = $response['total'];

            foreach ($response['rows'] as $row) {
                if ($times === null) {
                    $times = \App\Models\EarnApr::where('asset', $row['asset'])->pluck('time')->toArray();
                }
                if (in_array(intval($row['time'] / 1000), $times)) continue;
                $model = new \App\Models\EarnApr([
                    'asset' => $row['asset'],
                    'earn_apr' => $row['annualPercentageRate'],
                    'time' => intval($row['time'] / 1000)
                ]);
                $model->save();
            }
        } while ($current * $size < $total);

        return 0;
    }
}
