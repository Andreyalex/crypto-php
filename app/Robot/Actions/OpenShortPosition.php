<?php

namespace App\Robot\Actions;

use App\Exceptions\ActionException;
use App\Facades\Binance;
use App\Robot\Asset;
use Illuminate\Support\Facades\Log;
use function floor;
use function preg_match;
use function var_dump;

class OpenShortPosition
{
    public function __invoke($asset = 'ETH', $quotedAsset = 'USDT')
    {
        $order = null;

        //Binance::borrow($asset);

        $assetInfo = Binance::getMyCrossMarginAsset($asset);

        $amountToOrder = Asset::fixAmount($assetInfo['free'], $asset);
        try {
            $order = Binance::getRest()->marginNewOrder(
                $asset . $quotedAsset,
                'SELL',
                'MARKET',
                [
                    'quantity' => 0.01,
                    'sideEffectType' => 'MARGIN_BUY'
                ]
            );
        } catch (\Exception $e) {
            $code = Binance::getExceptionErrorCode($e);
            if ($code !== 2010) {
                throw new ActionException("FAILED creating an market order during SHORT position opening $amountToOrder $asset");
            }
        }

        if ($order) {
            $msg = "Opened SHORT position {$order['executedQty']} $asset";
            Log::info($msg);
            Log::channel('telegram')->info($msg);
        }

        return $order;
    }
}
