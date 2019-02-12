<?php
/**
 * Ikajo driver for the Omnipay PHP payment processing library
 *
 * @link      https://github.com/hiqdev/omnipay-ikajo
 * @package   omnipay-ikajo
 * @license   MIT
 * @copyright Copyright (c) 2019, HiQDev (http://hiqdev.com/)
 */

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
        $this->assertStringStartsWith('https://secure.serviceplatformpc.com/payment/auth', $response->getRedirectUrl());
        $this->assertSame([
            'key'            => $this->purse,
            'payment'        => 'CC',
            'url'            => $this->returnUrl,
            'error_url'      => $this->cancelUrl,
            'sign'           => '1b002db8a9aa2cbf0d43000c35e65d84',
            'data'           => 'W3siYW1vdW50IjoiMTQuNjUiLCJjdXJyZW5jeSI6IlVTRCIsImRlc2NyaXB0aW9uIjoiVGVzdCBUcmFuc2FjdGlvbiBsb25nIGRlc2NyaXB0aW9uIn1d',
            'order'          => $this->transactionId,
        ], $response->getRedirectData());
    }
}
