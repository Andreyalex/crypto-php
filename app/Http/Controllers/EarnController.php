<?php

namespace App\Http\Controllers;

use App\Models\EarnApr;
use function array_key_exists;
use function strtotime;

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
        foreach(EarnApr::orderBy('time')->get() as $item) {
            if (!array_key_exists($item['asset'], $charts)) {
                $charts[$item['asset']] = [];
            }
            $charts[$item['asset']][] = [
                'x' => $item->time * 1000,
                'y' => round($item->earn_apr * 10000) / 100
            ];
        }
        return view('earn-apr', [
            'charts' => $charts
        ]);
    }
}