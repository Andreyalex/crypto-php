<?php

namespace App\Robot\Actions;

use App\Exceptions\ActionException;
use App\Facades\Binance;
use App\Robot\Asset;
use Illuminate\Support\Facades\Log;

class CloseShortPosition
{
    public function __invoke($asset = 'ETH', $quotedAsset = 'USDT')
    {
        $order = null;

        $assets = Binance::getMyCrossMarginAssets();
        $amountToOrder = Asset::fixAmount($assets[$asset]['netAsset'] * -1.001, $asset); // also add the commission here

        // Borrow ony if it is available more than 0.01ETH
        if ($amountToOrder > Asset::getSmallestPart($asset) * 10) {
            $order = Binance::getRest()->marginNewOrder(
                $asset . $quotedAsset,
                'BUY',
                'MARKET',
                [
                    'quantity' => $amountToOrder
                ]
            );
            if (empty($order['orderId']))
                throw new ActionException("FAILED creating an market order during SHORT position closing $amountToOrder $asset");
        }

        Binance::repay($asset);

        if ($order) {
            $msg = "Closed SHORT position {$order['executedQty']} $asset";
            Log::info($msg);
            Log::channel('telegram')->info($msg);
        }

        return $order;
    }
}
