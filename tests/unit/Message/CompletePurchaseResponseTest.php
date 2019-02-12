<?php

namespace Omnipay\Ikajo\Tests\Message;

use Omnipay\Ikajo\Message\CompletePurchaseRequest;
use Omnipay\Ikajo\Message\CompletePurchaseResponse;
use Omnipay\Tests\TestCase;

class CompletePurchaseResponseTest extends TestCase
{
    /** @var CompletePurchaseRequest */
    private $request;

    private $purse  = 'vip.vip@corporation.incorporated';
    private $secret = '22SAD#-78G8sdf$88';

    private $id            = 'ikajoId';
    private $order         = 'ourId';
    private $status        = 'SALE';
    private $approval_code = '00';
    private $card          = '123456****1234';
    private $amount        = '0.01';
    private $currency      = 'USD';
    private $date          = '2015-12-12 12:12:12';
    private $name          = 'Jonh Doe';
    private $email         = 'foo@bar.baz';
    private $sign          = 'b7d35a5899828dd9791836fa4bc62f10';

    public function setUp()
    {
        parent::setUp();

        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'purse' => $this->purse,
            'secret' => $this->secret,
        ]);
    }

    public function testNotDoneException()
    {
        $this->setExpectedException('Omnipay\Common\Exception\InvalidResponseException', 'Transaction is not success: Bank is closed');
        new CompletePurchaseResponse($this->request, [
            'decline_reason' => 'Bank is closed',
        ]);
    }

    public function testInvalidHashException()
    {
        $this->setExpectedException('Omnipay\Common\Exception\InvalidResponseException', 'Invalid hash');
        new CompletePurchaseResponse($this->request, [
            'status' => $this->status
        ]);
    }

    public function testInvalidStatusException()
    {
        $this->setExpectedException('Omnipay\Common\Exception\InvalidResponseException', 'Transaction is not success: unknown reason');
        new CompletePurchaseResponse($this->request, [
            'status' => 'FAILED',
            'hash' => '491472c49b06b4dfc972c882f7bea14b',
        ]);
    }

    public function testSuccess()
    {
        $response = new CompletePurchaseResponse($this->request, [
            'id' => $this->id,
            'order' => $this->order,
            'status' => $this->status,
            'approval_code' => $this->approval_code,
            'card' => $this->card,
            'date' => $this->date,
            'name' => $this->name,
            'email' => $this->email,
            'sign' => $this->sign,
            'amount' => $this->amount,
            'currency' => $this->currency,
        ]);

        $this->assertTrue($response->isSuccessful());
        $this->assertSame($this->order, $response->getTransactionId());
        $this->assertSame($this->id, $response->getTransactionReference());
        $this->assertSame($this->amount, $response->getAmount());
        $this->assertSame($this->currency, $response->getCurrency());
        $this->assertSame($this->sign, $response->getSign());
        $this->assertFalse($response->isDeclined());

        $this->assertSame(strtotime($this->date), strtotime($response->getTime()));
    }
}
