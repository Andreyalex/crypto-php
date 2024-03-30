<?php

namespace App\Robot\Actions;

use App\Exceptions\ActionException;
use App\Facades\Binance;
use App\Robot\Asset;
use Illuminate\Support\Facades\Log;

class CloseLongPosition
{
    public function __invoke($asset = 'ETH', $quotedAsset = 'USDT')
    {
        $order = null;

        $assets = Binance::getMyCrossMarginAssets();
        $amountToOrder = Asset::fixAmount($assets[$asset]['free'], $asset);

        // Borrow ony if it is available more than 0.001ETH
        if ($amountToOrder > Asset::getSmallestPart($asset) * 10) {
            $order = Binance::getRest()->marginNewOrder(
                $asset . $quotedAsset,
                'SELL',
                'MARKET',
                [
                    'quantity' => $amountToOrder
                ]
            );
            if (empty($order['orderId']))
                throw new ActionException("FAILED creating an market order during position closing LONG $amountToOrder $asset");
        }

        Binance::repay($quotedAsset);

        if ($order) {
            $msg = "Closed LONG position {$order['executedQty']} $asset";
            Log::info($msg);
            Log::channel('telegram')->info($msg);
        }

        return $order;
    }
}
