<?php

namespace Omnipay\Pagarme\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class RefundRequest extends BaseRequest
{
    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint . 'transactions/' . $this->getTransactionReference() . '/refund';
    }

    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('transactionReference');
        return [];
    }
}