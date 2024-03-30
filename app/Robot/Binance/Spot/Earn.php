<?php

namespace App\Robot\Binance\Spot;

trait Earn
{
    public function flexibleList($options = [])
    {
        return $this->signRequest('GET', '/sapi/v1/simple-earn/flexible/list', $options);
    }
}
