<?php

namespace Omnipay\Pagarme\Message;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Pagarme\CreditCard;

abstract class BaseRequest extends AbstractRequest
{
    /**
     * Pagar.me API endpoint
     * @var string
     */
    protected string $endpoint = 'https://api.pagar.me/1/';

    /**
     * Default HTTP Method. Can be overridden
     * @return string
     */
    public function getHttpMethod(): string
    {
        return 'POST';
    }

    /**
     * @return mixed|CreditCard
     */
    public function getCard()
    {
        return $this->getParameter('card');
    }

    /**
     * Get card data according to Pagarme API
     *
     * @return array
     * @throws InvalidCreditCardException
     * @throws InvalidRequestException
     */
    protected function getCardData(): array
    {
        $card = $this->getCard();
        $card->validate();

        $data['object'] = 'card';
        $data['card_number'] = $card->getNumber();
        $data['card_expiration_date'] = sprintf('%02d', $card->getExpiryMonth() . $card->getExpiryYear());

        if ($card->getCvv())
            $data['card_cvv'] = $card->getCvv();

        $data['card_holder_name'] = $card->getName();

        return $data;
    }

    /**
     * @param $value
     * @return AbstractRequest
     */
    public function setCard($value): AbstractRequest
    {
        if ($value && !$value instanceof CreditCard)
            $value = new CreditCard($value);

        return $this->setParameter('card', $value);
    }

    /**
     * @return string|null
     */
    public function getApiKey(): ?string
    {
        return $this->getParameter('apiKey');
    }

    /**
     * @param string $value
     * @return BaseRequest
     */
    public function setApiKey(string $value): BaseRequest
    {
        return $this->setParameter('apiKey', $value);
    }

    /**
     * @return array|null
     */
    public function getCustomer(): ?array
    {
        return $this->getParameter('customer');
    }

    /**
     * @return string|null
     */
    public function getCardHash(): ?string
    {
        return $this->getParameter('card_hash');
    }

    /**
     * @param string $value
     * @return BaseRequest
     */
    public function setCardHash(string $value): BaseRequest
    {
        return $this->setParameter('card_hash', $value);
    }

    /**
     * @return array|null
     */
    public function getMetadata(): ?array
    {
        return $this->getParameter('metadata');
    }

    /**
     * @param array $value
     * @return BaseRequest
     */
    public function setMetadata(array $value): BaseRequest
    {
        return $this->setParameter('metadata', $value);
    }

    /**
     * Get Pagar.me API endpoint
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @param $data
     * @return Response
     */
    public function sendData($data): Response
    {
        $request = $this->httpClient->request(
            $this->getHttpMethod(),
            $this->getEndpoint(),
            [
                'Content-Type' => 'application/json'
            ],
            json_encode(['api_key' => $this->getApiKey()])
        );

        $payload = json_decode($request->getBody()->getContents(), true);
        return $this->response = new Response($this, $payload);
    }

}