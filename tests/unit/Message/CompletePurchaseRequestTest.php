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

use Omnipay\Ikajo\Message\CompletePurchaseRequest;
use Omnipay\Ikajo\Message\CompletePurchaseResponse;
use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class CompletePurchaseRequestTest extends TestCase
{
    private $purse                  = 'vip.vip@corporation.inc';
    private $secret                 = '22SAD#-78G888';

    private function createCompletePurchaseRequest(HttpRequest $httpRequest): CompletePurchaseRequest
    {
        $request = new CompletePurchaseRequest($this->getHttpClient(), $httpRequest);
        $request->initialize([
            'purse'     => $this->purse,
            'secret'    => $this->secret,
        ]);

        return $request;
    }

    private function mockHttpRequest(array $params = []): HttpRequest
    {
        return new HttpRequest([], [
            'id' => 'ikajoTransactionId',
            'order' => '123',
            'status' => 'SALE',
            'amount' => '10.12',
            'currency' => 'USD',
            'name' => 'John Doe',
            'email' => 'jd@gmail.com',
            'sign' => '1558e1355bc197bfc27bdaa74a826db8',
        ], $params);
    }

    public function testMappingIsCorrect(): void
    {
        $httpRequest = $this->mockHttpRequest();
        $request = $this->createCompletePurchaseRequest($httpRequest);

        $this->assertEquals($httpRequest->request->all(), $request->getData());
    }

    public function testSendData()
    {
        $httpRequest = $this->mockHttpRequest();
        $request = $this->createCompletePurchaseRequest($httpRequest);

        $response = $request->sendData($request->getData());
        $this->assertInstanceOf(CompletePurchaseResponse::class, $response);
    }
}
