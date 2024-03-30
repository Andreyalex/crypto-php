<?php

namespace App\Robot;

use InvalidArgumentException;
use function floor;
use function in_array;

class Asset
{
    static $roundFactor = [
        'ETH' => 0.0001,
        'USDT' => 0.01
    ];

    static $minNotion = [
        'USDT' => 10
    ];

    public static function getMinNotion($asset): float
    {
        if (!isset(self::$minNotion[$asset]))
            throw new InvalidArgumentException("Asset $asset minimal notion does not configured");

        return self::$minNotion[$asset];
    }

    public static function fixAmount($amount,  $asset): float
    {
        if (!isset(self::$roundFactor[$asset]))
            throw new InvalidArgumentException("Asset $asset smallest part does not configured");

        $factor = 1 / self::$roundFactor[$asset];

        return floor($amount * $factor) / $factor;
    }
}
