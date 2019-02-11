<?php

namespace Omnipay\Ikajo\Tests;

use Omnipay\Ikajo\Gateway;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /** @var Gateway */
    public $gateway;

    private $purse          = 'purse@company.co';
    private $secret         = 'sDf#$Sdf#$%';
    private $transactionId  = 'sadf2345asf';
    private $description    = 'Test completePurchase description';
    private $currency       = 'USD';
    private $amount         = '12.46';

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setPurse($this->purse);
        $this->gateway->setSecret($this->secret);
    }

    public function testGateway()
    {
        $this->assertSame($this->purse,     $this->gateway->getPurse());
        $this->assertSame($this->secret,    $this->gateway->getSecret());
    }

    public function testCompletePurchase()
    {
        $request = $this->gateway->completePurchase([
            'transactionId' => $this->transactionId,
        ]);

        $this->assertSame($this->purse,         $request->getPurse());
        $this->assertSame($this->secret,        $request->getSecret());
        $this->assertSame($this->transactionId, $request->getTransactionId());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase([
            'transactionId' => $this->transactionId,
            'description'   => $this->description,
            'currency'      => $this->currency,
            'amount'        => $this->amount,
        ]);

        $this->assertSame($this->transactionId, $request->getTransactionId());
        $this->assertSame($this->description,   $request->getDescription());
        $this->assertSame($this->currency,      $request->getCurrency());
        $this->assertSame($this->amount,        $request->getAmount());
    }
}
