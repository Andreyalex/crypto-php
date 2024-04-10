<?php

namespace App\Http\Controllers;

use App\Models\EarnApr;
use App\Models\Market;
use function array_key_exists;
use function explode;
use Illuminate\Database\Query\Expression;

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
        $earnData = EarnApr
            ::whereIn('asset', explode(',', env('BINANCE_EARN_APR_ASSETS', 'USDT')))
            ->orderBy('time')
            ->get();

        $marketData = Market
            ::whereIn('asset', explode(',', env('BINANCE_MARKET_ASSETS', 'BTCUSDT')))
            ->orderBy('time')
            ->get();

        $marketVolume = Market
            ::select([new Expression('sum(`volume`) as volume'), new Expression('min(`time`) as time')])
            ->whereIn('asset', explode(',', env('BINANCE_MARKET_ASSETS', 'BTCUSDT')))
            ->groupBy(new Expression('floor(`time`/1)'))
            ->orderBy('time')
            ->get();

        foreach($earnData as $item) {
            if (!array_key_exists($item['asset'], $charts)) {
                $charts[$item['asset']] = [
                    'type' => 'lines',
                    'data' => []
                ];
            }
            $charts[$item['asset']]['data'][] = [
                'x' => $item->time * 1000,
                'y' => round($item->earn_apr * 10000) / 100
            ];
        }
        foreach($marketData as $item) {
            if (!array_key_exists($item['asset'], $charts)) {
                $charts[$item['asset']] = [
                    'type' => 'lines',
                    'data' => []
                ];
            }
            $charts[$item['asset']]['data'][] = [
                'x' => $item->time * 1000,
                'y' => ($item->c)
            ];
        }
        foreach($marketVolume as $item) {
            if (!array_key_exists('BTCUSDT volume', $charts)) {
                $charts['BTCUSDT volume'] = [
                    'type' => 'bars',
                    'data' => []
                ];
            }
            $charts['BTCUSDT volume']['data'][] = [
                'x' => $item->time * 1000,
                'y' => ($item->volume)
            ];
        }
        return view('earn-apr', [
            'charts' => $charts
        ]);
    }
}