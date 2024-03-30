<?php

namespace App\Robot\Actions;

use App\Exceptions\ActionException;
use App\Facades\Binance;
use App\Robot\Asset;
use Illuminate\Support\Facades\Log;
use function floor;

class OpenLongPosition
{
    public function __invoke($asset = 'ETH', $quotedAsset = 'USDT')
    {
        $order = null;

        Binance::borrow($quotedAsset);

        $quotedAssetInfo = Binance::getMyCrossMarginAsset($quotedAsset);

        $amountToOrder = Asset::fixAmount($quotedAssetInfo['free'], $quotedAsset);
        if ($amountToOrder > Asset::getMinNotion($quotedAsset)) {
            $order = Binance::getRest()->marginNewOrder(
                $asset . $quotedAsset,
                'BUY',
                'MARKET',
                [
                    'quoteOrderQty' => $amountToOrder
                ]
            );
            if (empty($order['orderId']))
                throw new ActionException("FAILED creating an market order during LONG position opening $amountToOrder $asset");

            $assetInfo = Binance::getMyCrossMarginAsset($asset);
            $amountToOrder = Asset::fixAmount($assetInfo['free'], $asset);
            Binance::getRest()->marginNewOcoOrder(
                $asset . $quotedAsset,
                'SELL',
                $amountToOrder,
                400.15,
                390.3,
                [
                    'stopLimitPrice' => 290,
                    'stopLimitTimeInForce' => 'GTC',
                    'recvWindow' => 5000
                ]
            );
        }

        if ($order) {
            $msg = "Opened LONG position {$order['executedQty']} $asset";
            Log::info($msg);
            Log::channel('telegram')->info($msg);
        }

        return $order;
    }
}
