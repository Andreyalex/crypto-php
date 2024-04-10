<?php

namespace App\Http\Controllers;

use App\Models\EarnApr;
use App\Models\Market;
use function array_key_exists;
use function explode;

class EarnController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function aprChart()
    {
        $charts = [];
        $data = EarnApr
            ::whereIn('asset', explode(',', env('BINANCE_EARN_APR_ASSETS', 'USDT')))
            ->orderBy('time')
            ->get();

        $marketData = Market
            ::whereIn('asset', explode(',', env('BINANCE_MARKET_ASSETS', 'BTCUSDT')))
            ->orderBy('time')
            ->get();

        foreach($data as $item) {
            if (!array_key_exists($item['asset'], $charts)) {
                $charts[$item['asset']] = [];
            }
            $charts[$item['asset']][] = [
                'x' => $item->time * 1000,
                'y' => round($item->earn_apr * 10000) / 100
            ];
        }
        foreach($marketData as $item) {
            if (!array_key_exists($item['asset'].' market', $charts)) {
                $charts[$item['asset'].' market'] = [];
            }
            $charts[$item['asset'].' market'][] = [
                'x' => $item->time * 1000,
                'y' => ($item->c)
            ];
        }
        return view('earn-apr', [
            'charts' => $charts
        ]);
    }
}