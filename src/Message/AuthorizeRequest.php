<?php

namespace Omnipay\Pagarme\Message;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;

class AuthorizeRequest extends BaseRequest
{
    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint . 'transactions';
    }

    /**
     * @return string|null
     */
    public function getPostbackUrl(): ?string
    {
        return $this->getParameter('postback_url');
    }

    /**
     * @param bool $value
     * @return AuthorizeRequest
     */
    public function setCapture(bool $value): AuthorizeRequest
    {
        return $this->setParameter('capture', $value);
    }

    /**
     * @return bool
     */
    public function shouldAuthorizeAndCapture(): bool
    {
        return $this->getParameter('capture') == true;
    }

    /**
     * @param $value
     * @return AuthorizeRequest
     */
    public function setPostbackUrl($value): AuthorizeRequest
    {
        return $this->setParameter('postback_url', $value);
    }

    /**
     * @return int|null
     */
    public function getInstallments(): ?int
    {
        return $this->getParameter('installments');
    }

    /**
     * @param $value
     * @return AuthorizeRequest
     */
    public function setInstallments($value): AuthorizeRequest
    {
        return $this->setParameter('installments', $value);
    }

    /**
     * @return string|null
     */
    public function getSoftDescriptor(): ?string
    {
        return $this->getParameter('soft_descriptor');
    }


    /**
     * @return string|null
     */
    public function getBoletoExpirationDate(): ?string
    {
        return $this->getParameter('boleto_expiration_date');
    }

    /**
     * @param $value
     * @return AuthorizeRequest
     */
    public function setBoletoExpirationDate($value): AuthorizeRequest
    {
        $date = date('Y-m-d', strtotime($value));
        return $this->setParameter('boleto_expiration_date', $date ?: null);
    }

    /**
     * @return string|null
     */
    public function getBoletoInstructions(): ?string
    {
        return $this->getParameter('boleto_instructions');
    }

    /**
     * @param string $value
     * @return AuthorizeRequest
     */
    public function setBoletoInstructions(string $value): AuthorizeRequest
    {
        return $this->setParameter('boleto_instructions', substr($value, 0, 255));
    }

    /**
     * Set soft descriptor
     * Pagar.me allow max 13 characters
     * @param $value
     * @return AbstractRequest
     */
    public function setSoftDescriptor($value): AbstractRequest
    {
        return $this->setParameter('soft_descriptor', substr($value, 0, 13));
    }

    /**
     * @return array
     */
    public function getCardCustomerData(): array
    {
        $card = $this->getCard();

        $data = [];
        $data['customer']['name'] = $card->getBillingName();
        $data['customer']['email'] = $card->getEmail();
        $data['customer']['country'] = strtolower($card->getCountry());
        $data['customer']['type'] = $card->getHolderCustomerType();
        $data['customer']['documents'] = [[
            'type' =>  $card->getHolderDocumentType(),
            'number' => $card->getHolderDocumentNumber()
        ]];
        $data['customer']['phone_numbers'] = [$card->getBillingPhone()];

        return $data;
    }

    /**
     * @throws InvalidRequestException
     * @throws InvalidCreditCardException
     */
    public function getData()
    {
        $this->validate('amount');

        $data = [];
        $data['amount'] = $this->getAmountInteger();
        $data['payment_method'] = $this->getPaymentMethod();
        $data['postback_url'] = $this->getPostbackUrl();
        $data['installments'] = $this->getInstallments();
        $data['soft_descriptor'] = $this->getSoftDescriptor();
        $data['metadata'] = $this->getMetadata();

        switch ($data['payment_method']) {
            case 'boleto':
                $data['boleto_expiration_date'] = $this->getBoletoExpirationDate();
                break;
            case 'credit_card':
                $data['capture'] = $this->shouldAuthorizeAndCapture() ? 'true' : 'false';
                if ($this->getCard())
                    $data = array_merge($data, $this->getCardData(), $this->getCardCustomerData());
                if ($this->getCardHash())
                    $data['card_hash'] = $this->getCardHash();
                else if ($this->getCardReference())
                    $data['card_id'] = $this->getCardReference();
                else
                    $this->validate('card');
        }

        return $data;
    }
}