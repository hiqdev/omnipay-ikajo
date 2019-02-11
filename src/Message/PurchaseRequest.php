<?php

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
            'payment'        => $this->getPayment(),
            'url'            => $this->getReturnUrl(),
            'error_url'      => $this->getCancelUrl(),
            'sign'           => $this->signRequest()
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
