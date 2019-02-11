<?php

namespace Omnipay\Ikajo\Tests\Message;

use Omnipay\Ikajo\Message\PurchaseRequest;
use Omnipay\Tests\TestCase;

class PurchaseResponseTest extends TestCase
{
    /** @var PurchaseRequest */
    private $request;

    private $purse          = 'tip.top@corporation.inc';
    private $secret         = '12()&*&+_)?><';
    private $returnUrl      = 'https://www.foodstore.com/success';
    private $cancelUrl      = 'https://www.foodstore.com/failure';
    private $notifyUrl      = 'https://www.foodstore.com/notify';
    private $description    = 'Test Transaction long description';
    private $transactionId  = '12345ASD67890sd';
    private $amount         = '14.65';
    private $currency       = 'USD';
    private $testMode       = true;

    public function setUp()
    {
        parent::setUp();

        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'purse'         => $this->purse,
            'secret'        => $this->secret,
            'returnUrl'     => $this->returnUrl,
            'cancelUrl'     => $this->cancelUrl,
            'notifyUrl'     => $this->notifyUrl,
            'description'   => $this->description,
            'transactionId' => $this->transactionId,
            'amount'        => $this->amount,
            'currency'      => $this->currency,
            'testMode'      => $this->testMode,
        ]);
    }

    public function testSuccess()
    {
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getCode());
        $this->assertNull($response->getMessage());
        $this->assertSame('POST', $response->getRedirectMethod());
        $this->assertStringStartsWith('https://secure.payinspect.com/post', $response->getRedirectUrl());
        $this->assertSame([
            'payment'        => 'CC',
            'url'            => $this->returnUrl,
            'error_url'      => $this->cancelUrl,
            'sign'           => 'ec0eea3fca12c52e71bf37d2cbb1dab1',
            'order'          => $this->transactionId,
        ], $response->getRedirectData());
    }
}
