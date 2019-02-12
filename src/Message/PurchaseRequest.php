<?php
/**
 * Ikajo driver for the Omnipay PHP payment processing library
 *
 * @link      https://github.com/hiqdev/omnipay-ikajo
 * @package   omnipay-ikajo
 * @license   MIT
 * @copyright Copyright (c) 2019, HiQDev (http://hiqdev.com/)
 */

namespace Omnipay\Ikajo\Message;

class PurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate(
            'purse',
            'amount', 'currency', 'description',
            'returnUrl', 'cancelUrl'
        );

        $res = [
            'key'            => $this->getPurse(),
            'payment'        => $this->getPayment(),
            'url'            => $this->getReturnUrl(),
            'error_url'      => $this->getCancelUrl(),
            'sign'           => $this->signRequest(),
            'data'           => base64_encode(json_encode($this->getPaymentData())),
        ];

        if ($this->getTransactionId()) {
            $res['order'] = $this->getTransactionId();
        }

        return $res;
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
