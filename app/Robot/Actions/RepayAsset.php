<?php

namespace App\Robot\Actions;

use App\Exceptions\ActionException;
use App\Facades\Binance;
use App\Facades\Output;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Output\ConsoleOutput;

class RepayAsset
{
    public function __invoke(string $asset, float $amount)
    {
        $res = Binance::getRest()->marginRepay(
            $asset,
            $amount,
            [
                'recvWindow' => 5000
            ]
        );

        if (empty($res['tranId'])) {
            $msg = 'FAILED. Repay ' . $amount . ' ' . $asset;
            throw new ActionException($msg);
        }

        $msg = 'Repaid ' . $amount . ' ' . $asset;
        Log::info($msg);
        Log::channel('telegram')->info($msg);
    }
}
