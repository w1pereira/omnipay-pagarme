<?php

namespace Omnipay\Pagarme;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;

class Gateway extends AbstractGateway
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Pagarme';
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    /**
     * @param $value
     * @return Gateway
     */
    public function setApiKey($value): Gateway
    {
        return $this->setParameter('apiKey', $value);
    }

    /**
     * @param array $options
     * @return RequestInterface
     */
    public function authorize(array $options = array()): RequestInterface
    {
        return $this->createRequest('\Omnipay\Pagarme\Message\AuthorizeRequest', $options);
    }

    /**
     * @param array $options
     * @return RequestInterface
     */
    public function purchase(array $options = array()): RequestInterface
    {
        return $this->createRequest('\Omnipay\Pagarme\Message\PurchaseRequest', $options);
    }

    /**
     * @param array $options
     * @return RequestInterface
     */
    public function refund(array $options = array()): RequestInterface
    {
        return $this->createRequest('\Omnipay\Pagarme\Message\RefundRequest', $options);
    }
}