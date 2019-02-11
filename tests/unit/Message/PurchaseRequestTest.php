<?php

namespace Omnipay\Ikajo\Tests\Message;

use Omnipay\Ikajo\Message\PurchaseRequest;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    /** @var \Omnipay\Ikajo\Message\PurchaseRequest */
    private $request;

    private $purse          = 'vip.vip@corporation.inc';
    private $secret         = '22SAD#-78G888';
    private $returnUrl      = 'https://www.foodstore.com/success';
    private $cancelUrl      = 'https://www.foodstore.com/failure';
    private $description    = 'Test Transaction long description';
    private $transactionId  = '12345ASD67890sd';
    private $amount         = '14.65';
    private $currency       = 'USD';

    public function setUp()
    {
        parent::setUp();

        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'purse'         => $this->purse,
            'secret'        => $this->secret,
            'returnUrl'     => $this->returnUrl,
            'cancelUrl'     => $this->cancelUrl,
            'description'   => $this->description,
            'transactionId' => $this->transactionId,
            'amount'        => $this->amount,
            'currency'      => $this->currency,
        ]);
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame($this->transactionId, $data['order']);
        $this->assertSame($this->returnUrl,     $data['url']);
        $this->assertSame($this->cancelUrl,     $data['error_url']);
        $this->assertSame('CC',        $data['payment']);
    }

    public function testSendData()
    {
        $data = $this->request->getData();
        $response = $this->request->sendData($data);
        $this->assertInstanceOf(\Omnipay\Ikajo\Message\PurchaseResponse::class, $response);
    }
}
