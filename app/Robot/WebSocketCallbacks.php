<?php

namespace App\Robot;

class WebSocketCallbacks
{
    protected $wsConnector;

    protected $callbacks;

    public function __construct($wsConnector)
    {
        $this->wsConnector = $wsConnector;
    }

    public function bind($method, $asset, $type, $callback)
    {

    }
}
