<?php

namespace App\Robot\Pollers;

use App\Facades\Binance;
use App\Robot\Actions\RepayAsset;

class CheckAmountToRepay
{
    public function __invoke()
    {
        $assets = Binance::getMyCrossMarginAssets(['ETH', 'USDT']);

        foreach ($assets as $asset) {
            if ($asset['borrowed'] > 0 && $asset['free'] > 0) {
                $action = new RepayAsset();
                $action(
                    $asset['asset'],
                    $asset['free'] < $asset['borrowed']? $asset['free'] : $asset['borrowed']
                );
            }
        }
    }
}
