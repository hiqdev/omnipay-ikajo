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

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Ikajo Complete Purchase Response.
 */
class CompletePurchaseResponse extends AbstractResponse
{
    /** @var RequestInterface|CompletePurchaseRequest */
    protected $request;

    /**
     * CompletePurchaseResponse constructor.
     *
     * @param RequestInterface $request
     * @param $data
     * @throws InvalidResponseException
     */
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        if ($this->getTransactionStatus() !== 'SALE' || $this->isDeclined()) {
            $reason = $this->getErrorReason() ?? 'unknown reason';
            throw new InvalidResponseException('Transaction is not success: ' . $reason);
        }

        if ($this->getSign() !== $this->calculateSign()) {
//            error_log("hashes: '" . $this->getSign() . "' - '" . $this->calculateSign() . "'\n");
            throw new InvalidResponseException('Invalid hash');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getTransactionId()
    {
        return $this->data['order'];
    }

    public function getErrorReason(): ?string
    {
        return $this->data['decline_reason'] ?? null;
    }

    public function isDeclined(): bool
    {
        return $this->getErrorReason() !== null && $this->getErrorReason() !== '';
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getTransactionReference()
    {
        return $this->data['id'];
    }

    public function getTransactionStatus()
    {
        return $this->data['status'];
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getAmount()
    {
        return $this->data['amount'];
    }

    /**
     * Get payment time.
     *
     * @return string
     */
    public function getTime()
    {
        $time = new \DateTime($this->data['date'], new \DateTimeZone('UTC'));

        return $time->format('c');
    }

    /**
     * Get payment currency.
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->data['currency'];
    }

    /**
     * Get payer info - name, username and id.
     *
     * @return string
     */
    public function getPayer()
    {
        return implode(' / ', array_filter([
            $this->data['name'] ?? null,
            $this->data['email'] ?? null,
            $this->data['card'] ?? null,
            $this->data['RRN'] ?? null,
        ]));
    }

    /**
     * Get hash from request.
     *
     * @return string
     */
    public function getSign()
    {
        return $this->data['sign'];
    }

    /**
     * Calculate hash to validate incoming IPN notifications.
     *
     * @return string
     */
    public function calculateSign()
    {
        return md5(strtoupper(implode('', [
            strrev($this->data['email'] ?? ''),
            $this->request->getSecret(),
            $this->getTransactionId(),
            strrev(
                substr($this->data['card'], 0, 6)
                . substr($this->data['card'], -4)
            ),
        ])));
    }
}
