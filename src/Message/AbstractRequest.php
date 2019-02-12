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

/**
 * Ikajo Abstract Request.
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $zeroAmountAllowed = false;

    /**
     * Get the merchant purse.
     *
     * @return string merchant purse - Key for Client identification
     */
    public function getPurse()
    {
        return $this->getParameter('purse');
    }

    /**
     * Set the purse.
     *
     * @param string $value merchant purse - Key for Client identification
     * @return self
     */
    public function setPurse($value)
    {
        return $this->setParameter('purse', $value);
    }

    /**
     * Get the merchant secret.
     *
     * @return string merchant secret
     */
    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    /**
     * Set the merchant secret.
     *
     * @param string $value merchant secret
     * @return self
     */
    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    /**
     * Credit Card. Constant and the only available value.
     *
     * @return string
     */
    public function getPayment()
    {
        return 'CC';
    }

    public function getPaymentData()
    {
        return [[
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'description' => $this->getDescription(),
        ]];
    }

    public function signRequest()
    {
        return md5(strtoupper(
            implode('',
                array_map('strrev', [
                    $this->getPurse(),
                    $this->getPayment(),
                    base64_encode(json_encode($this->getPaymentData())),
                    $this->getReturnUrl(),
                    $this->getSecret(),
                ])
            )
        ));
    }
}
