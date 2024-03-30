<?php

namespace App\Robot;

use App\Robot\Detectors\HighVolume;
use App\Robot\Detectors\Peak;
use App\Robot\Detectors\PriceRush;
use App\Robot\Pollers\CheckAmountToRepay;
use Illuminate\Console\OutputStyle;
use Illuminate\Support\Facades\Log;
use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;
use React\EventLoop\StreamSelectLoop;
use Symfony\Component\Console\Output\OutputInterface;
use const PHP_EOL;

class Daemon
{
    /**
     * @var OutputInterface
     */
    protected $output;


    protected $detectors = [];

    protected $pollers = [];

    public function __construct(OutputStyle $output)
    {
        $this->output = $output;
    }

    public function start()
    {


        $this->initialize();

        Loop::addPeriodicTimer(5, function () {
            $this->invokePollers();
        });
//        $callbacks = [
//            'message' => function($conn, $msg) {
//                 $this->output->writeln($msg);
//            },
//            'ping' => function($conn, $msg) {
//                $this->output->note('received ping from server');
//            }
//        ];
//
//        $client->aggTrade('btcusdt', $callbacks);
    }

    public function initialize()
    {
//        $this->detectors['highVolume'] = new HighVolume($this->output);
//        $this->detectors['peack'] = new Peak();
//        $this->detectors['priceRush'] = new PriceRush();

        $this->pollers['checkAmountToRepay'] = new CheckAmountToRepay();
    }

    public function invokePollers()
    {
        foreach ($this->pollers as $pollerName => $poller) {
            Log::debug('Invoking ' . $pollerName . '..');
            try {
                $poller();
            } catch (\Throwable $e) {
                Log::error($e->getMessage());
            }
        }
    }
}
