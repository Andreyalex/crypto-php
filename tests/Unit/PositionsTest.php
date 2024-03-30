<?php

namespace Tests\Unit;

use App\Robot\Actions\CloseLongPosition;
use App\Robot\Actions\CloseShortPosition;
use App\Robot\Actions\OpenLongPosition;
use App\Robot\Actions\OpenShortPosition;
use Tests\TestCase;

class PositionsTest extends TestCase
{
    public function test_openLongPosition()
    {
        $action = new OpenLongPosition();
        $order = $action('ETH');
        $this->assertIsNumeric($order['orderId']);
    }

    public function test_closeLongPosition()
    {
        $action = new CloseLongPosition();
        $order = $action('ETH');
        $this->assertIsNumeric($order['orderId']);
    }

    public function test_openShortPosition()
    {
        $action = new OpenShortPosition();
        $order = $action('ETH');
        $this->assertIsNumeric($order['orderId']);
    }

    public function test_closeShortPosition()
    {
        $action = new CloseShortPosition();
        $order = $action( 'ETH');
        $this->assertIsNumeric($order['orderId']);
    }
}
