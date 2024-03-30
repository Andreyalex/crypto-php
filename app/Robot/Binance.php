<?php

namespace App\Robot;

use App\Exceptions\ActionException;
use Binance\Spot;
use function env;
use function in_array;
use function preg_match;
use function var_dump;

class Binance
{
    protected $rest;

    protected $websocket;

    public function __construct($key, $secret, $testNet)
    {
        $options = [
            'key' => $key,
            'secret' => $secret,
        ];
        if ($testNet) $options['baseURL'] = 'https://testnet.binance.vision';

        $this->rest = new \Binance\Spot($options);
        $this->websocket = new \Binance\Websocket\Spot();
    }

    public function getMyCrossMarginAssets(array $assets = ['ETH', 'USDT']): array
    {
        $payload = $this->rest->marginAccount([
                'recvWindow' => 5000
        ]);

        $res = [];
        foreach ($payload['userAssets'] as $asset) {
            if ($assets && in_array($asset['asset'], $assets)) $res[$asset['asset']] = $asset;
        }

        return $res;
    }

    public function getMyCrossMarginAsset(string $asset): array
    {
        return $this->getMyCrossMarginAssets([$asset])[$asset];
    }

    public function borrow($asset, $amount = null): ?array
    {
        if (!$amount) {
            $res = $this->getRest()->marginMaxBorrowable($asset);
            $amount = $res['amount'];
        }

        $amount = Asset::fixAmount($amount, $asset);

        // Borrow ony if it is available more than $1 or 0.01ETH
        if ($amount > Asset::getSmallestPart($asset) * 10) {
            $res = Binance::getRest()->marginBorrow(
                $asset,
                $amount
            );
            if (empty($res['tranId']))
                throw new ActionException("Borrowing failed: $amount $asset");
        }

        return $res?? null;
    }

    public function repay($asset, $amount = null)
    {
        if (!$amount) {
            $assets = $this->getMyCrossMarginAssets();
            $amount = min((float) $assets[$asset]['borrowed'], (float) $assets[$asset]['free']);
        }

        $amount = Asset::fixAmount($amount, $asset);

        if ($amount > Asset::getSmallestPart($asset) * 10) {
            $repay = $this->getRest()->marginRepay(
                $asset,
                $amount
            );
            if (empty($repay['tranId']))
                throw new ActionException("Repayment failed $amount $asset");
        }
    }

    public function getExceptionErrorCode(\Exception $e): ?int
    {
        preg_match("/\"code\"\:\-([0-9]+)/", $e->getMessage(), $matches);
        return $matches[1]?? null;
    }

    /**
     * @return Spot
     */
    public function getRest(): Spot
    {
        return $this->rest;
    }
}
