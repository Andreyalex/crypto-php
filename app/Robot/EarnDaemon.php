<?php

namespace App\Robot;

use Illuminate\Console\OutputStyle;
use Illuminate\Support\Facades\Log;
use React\EventLoop\Loop;
use Symfony\Component\Console\Output\OutputInterface;

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
        try {
            $this->binanceClient = new \App\Robot\Binance\Spot([
                'key' => env('BINANCE_API_KEY'),
                'secret' => env('BINANCE_API_SECRET')
            ]);

            $seconds = 60;
            Loop::addPeriodicTimer($seconds, function () {
                $this->pullSimpleEarnApr();
            });
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }

    protected function pullSimpleEarnApr()
    {
        $response = $this->binanceClient->flexibleList(['asset' => 'USDT']);
        if (array_key_exists('rows', $response)) {
            foreach ($response['rows'] as $row) {
                $model = new \App\Models\EarnApr([
                    'asset' => $row['asset'],
                    'earn_apr' => $row['latestAnnualPercentageRate']
                ]);
                $model->save();
            }
        }
    }
}
