<?php

namespace App\Http\Controllers;

use App\Models\EarnApr;
use App\Models\User;

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
        $x = [];
        $y = [];
        foreach(EarnApr::all() as $item) {
            $x[] = $item->created_at->timestamp * 1000;
            $y[] = round($item->earn_apr * 10000) / 100;
        }

        return view('earn-apr', [
            'earnApr' => [
                'title' => 'Simple Earn APR',
                'x' => $x,
                'y' => $y,
                'minY' => min($y),
                'maxY' => max($y)
            ]
        ]);
    }
}