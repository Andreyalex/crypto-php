<?php

namespace App\Robot;

use function explode;
use Illuminate\Console\OutputStyle;
use Illuminate\Support\Facades\Log;
use React\EventLoop\Loop;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class EarnDaemon
{
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var \App\Robot\Binance\Spot
     */
    protected $binanceClient;

    public function __construct(OutputStyle $output)
    {
        $this->output = $output;
    }

    public function start()
    {
        $this->binanceClient = new \App\Robot\Binance\Spot([
            'key' => env('BINANCE_API_KEY'),
            'secret' => env('BINANCE_API_SECRET')
        ]);

        $seconds = env('BINANCE_EARN_APR_ASSETS_FETCH_PERIOD', 60);
        // first time right now. Next will be in {$seconds} seconds
        $this->pullSimpleEarnApr();
        Loop::addPeriodicTimer($seconds, function () {
            try {
                $this->pullSimpleEarnApr();
            } catch (Throwable $e) {
                Log::error($e);
                throw $e;
            }
        });
    }

    protected function pullSimpleEarnApr()
    {
        $assets = explode(',', env('BINANCE_EARN_APR_ASSETS', 'USDT'));
        foreach ($assets as $asset) {
            $response = $this->binanceClient->earnFlexibleList(['asset' => $asset]);
            if (array_key_exists('rows', $response)) {
                foreach ($response['rows'] as $row) {
                    $model = new \App\Models\EarnApr([
                        'asset' => $row['asset'],
                        'earn_apr' => $row['latestAnnualPercentageRate'],
                        'time' => time()
                    ]);
                    $model->save();
                }
            }
        }
    }
}
