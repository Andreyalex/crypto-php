<?php

namespace App\Robot\Binance\Spot;

trait Earn
{
    public function earnFlexibleList($options = [])
    {
        return $this->signRequest('GET', '/sapi/v1/simple-earn/flexible/list', $options);
    }
    public function earnFlexibleRateHistory($options = [])
    {
        return $this->signRequest('GET', '/sapi/v1/simple-earn/flexible/history/rateHistory', $options);
    }
}
