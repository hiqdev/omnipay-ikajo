<?php
/**
 * Ikajo driver for the Omnipay PHP payment processing library
 *
 * @link      https://github.com/hiqdev/omnipay-ikajo
 * @package   omnipay-ikajo
 * @license   MIT
 * @copyright Copyright (c) 2019, HiQDev (http://hiqdev.com/)
 */

namespace Omnipay\Ikajo;

use Omnipay\Common\AbstractGateway;
use Omnipay\Ikajo\Message\CompletePurchaseRequest;
use Omnipay\Ikajo\Message\PurchaseRequest;

class Gateway extends AbstractGateway
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Ikajo';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultParameters()
    {
        return [
            'purse'     => '',
            'secret'    => '',
            'testMode'  => false,
        ];
    }

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
     * Set the merchant purse.
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
     * @param array $parameters
     *
     * @return \Omnipay\Ikajo\Message\PurchaseRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return \Omnipay\Ikajo\Message\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }
}
