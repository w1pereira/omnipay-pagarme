<?php

namespace Omnipay\Pagarme;

use Omnipay\Common\CreditCard as Card;

class CreditCard extends Card
{
    /**
     * @return string
     */
    public function getHolderDocumentNumber(): string
    {
        return $this->getParameter('holder_document_number');
    }

    /**
     * @param $value
     * @return CreditCard
     */
    public function setHolderDocumentNumber($value): CreditCard
    {
        return $this->setParameter('holder_document_number', preg_replace('/\D/', '', $value));
    }

    /**
     * @return string
     */
    public function getHolderDocumentType(): string
    {
        return $this->getParameter('holder_document_type');
    }

    /**
     * @param $value
     * @return CreditCard
     */
    public function setHolderDocumentType($value): CreditCard
    {
        return $this->setParameter('holder_document_type', $value);
    }

    /**
     * @return string
     */
    public function getHolderCustomerType(): string
    {
        return $this->getParameter('holder_customer_type');
    }

    /**
     * @param $value
     * @return string
     */
    public function setHolderCustomerType($value): CreditCard
    {
        return $this->setParameter('holder_customer_type', $value);
    }
}