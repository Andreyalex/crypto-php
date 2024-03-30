<?php

namespace App\Robot\Binance;

use App\Robot\Binance\Spot\Earn;
use Binance\Spot as BinanceSpot;

class Spot extends BinanceSpot
{
    use Earn;

}
