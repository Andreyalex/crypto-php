<?php

namespace App\Robot\Detectors;

use App\Robot\Buffer;
use Binance\Spot;
use Illuminate\Console\OutputStyle;
use function collect;
use function floatval;

class HighVolume
{
    protected $output;

    protected $bufferVolumes;

    protected $extraHighVolRatio = 4;

    protected $persistentHighVolRatio = 2;

    public function __construct(Spot $restClient, \Binance\Websocket\Spot $wsConnector, OutputStyle $output)
    {
        $this->output = $output;

        $this->bufferVolumes = new Buffer(60,
            collect($restClient->klines('ethusdt', '1m', ['limit' => '61']))
                ->pop()
                ->map(function($item) {
                    return floatval($item[5]);
                })
        );

        $wsConnector->kline('ethusdt', '1m', function($msg) {
            $this->output->info($msg);
            $this->onTick(floatval($msg['k']['v']), $msg['k']['x'], $msg['k']['t']);
        });
    }

    protected function onTick($volumeCurrent, $isCandleClosed, $timestamp)
    {
        $volumeAverage = $this->bufferVolumes->avg();
        $isExtraHigh = $volumeCurrent / $volumeAverage > $this->extraHighVolRatio;
        $isPersistentHigh = $volumeCurrent / $volumeAverage > $this->persistentHighVolRatio;

        # register and notify extra high volume
        if ($isExtraHigh) {
            $this->extraHighVol = $volumeCurrent;
            if ($this->extraHighVolNotified or $isCandleClosed) {
                $m = 'Extra high volumes detected: x' + str(round($volumeCurrent / $volumeAverage));
                $this->notify($m);
                $this->extraHighVolNotified = true;
            }
        }

        # check for high volume which persists some time
        if ($isPersistentHigh) {
            $this->highVolumesChain[$timestamp] = $volumeCurrent;
            if (len($this->highVolumesChain) >= 3 and (!$this->persistentHighVolNotified or $isCandleClosed)) {
                $m = 'High volumes detected for 3+ minutes';
                $this->output->info($m);
                $this->notify($m);
                playsound('mixkit-positive-notification-951.wav');
                $this->persistentHighVolNotified = true;
            }
        }

        # when candle closes:
        if ($isCandleClosed) {
            $this->bufferVolumes.push($volumeCurrent);
            # when candle closes we do some clearings:
            if (!$isExtraHigh) {
                $this->extraHighVol = 0;
                $this->extraHighVolNotified = false;
            }
            if (!$isPersistentHigh) {
                $this->highVolumesChain->clear();
                $this->persistentHighVolNotified = false;
            }

    }
}
