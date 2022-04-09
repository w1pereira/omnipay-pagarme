<?php

namespace Omnipay\Pagarme\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class CaptureRequest extends BaseRequest
{
    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint . 'transactions/' . $this->getTransactionReference() . '/capture';
    }

    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('transactionReference');
        return [
            'id_or_token' => $this->getTransactionReference(),
            'amount' => $this->getAmountInteger()
        ];
    }
}