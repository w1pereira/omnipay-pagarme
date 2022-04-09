<?php

namespace Omnipay\Pagarme\Message;

use Omnipay\Common\Message\AbstractResponse;

class Response extends AbstractResponse
{
    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        if (isset($this->data['object']) && 'transaction' == $this->data['object']) {
            return !($this->data['status'] == 'refused');
        }
        return !isset($this->data['errors']);
    }

    /**
     * @return mixed|string|null
     */
    public function getTransactionReference()
    {
        if (isset($this->data['object']) && 'transaction' === $this->data['object']) {
            return $this->data['id'];
        }
        return null;
    }

    /**
     * @return mixed|null
     */
    public function getCardReference()
    {
        if (isset($this->data['object'])) {
            if ('card' == $this->data['object'] && !empty($this->data['id']))
                return $this->data['id'];
            else if ('transaction' == $this->data['object'])
                return $this->data['card']['id'];
        }
        return null;
    }

    /**
     * @return mixed|null
     */
    public function getCustomerReference()
    {
        if (isset($this->data['object'])) {
            if ('customer' == $this->data['object'])
                return $this->data['id'];

            if (!empty($this->data['customer']) && in_array($this->data['object'], ['card', 'transaction']))
                return $this->data['customer']['id'];
        }
        return null;
    }

    /**
     * Get the error message from the response
     *
     * @return string|null
     */
    public function getMessage(): ?string
    {
        if (!$this->isSuccessful()) {
            if (isset($this->data['errors']))
                return $this->data['errors'][0]['message'];

            return $this->data['refuse_reason'];
        }
        return null;
    }

    /**
     * Get the boleto_url, boleto_barcode and boleto_expiration date
     *
     * @return array|null
     */
    public function getBoleto(): ?array
    {
        if (isset($this->data['object']) && 'transaction' == $this->data['object']
            && $this->data['boleto_url']) {
            return [
                'boleto_url' => $this->data['boleto_url'],
                'boleto_barcode' => $this->data['boleto_barcode'],
                'boleto_expiration_date' => $this->data['boleto_expiration_date']
            ];
        }
        return null;
    }
}