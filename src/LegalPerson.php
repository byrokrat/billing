<?php

namespace byrokrat\billing;

use byrokrat\id\Id;
use byrokrat\id\NullId;
use byrokrat\banking\AccountNumber;

/**
 * A LegalPerson is a container for id, accounts and more
 *
 * Legal persons are of two kinds: natural persons (people) and juridical persons,
 * organizations which are treated by law as if they were persons.
 */
class LegalPerson
{
    /**
     * @var string Name of this legal person
     */
    private $name;

    /**
     * @var Id Personal identifier
     */
    private $legalId;

    /**
     * @var AccountNumber Account registered with person
     */
    private $account;

    /**
     * @var string Optional customer number for billing
     */
    private $customerNr;

    /**
     * @var boolean Flag if this is an organization
     */
    private $isOrg;

    /**
     * @var string Optional VAT number for organizations
     */
    private $vatNr = '';

    /**
     * Construct legal person container
     *
     * @param string        $name       Name of legal person
     * @param Id            $legalId    Peronal identifier
     * @param AccountNumber $account    Account number
     * @param string        $customerNr Customer number for billing
     * @param boolean       $isOrg      Flag if this is an organization
     * @param boolean       $isVat      Flag if organization is registered for VAT
     */
    public function __construct(
        $name,
        Id $legalId = null,
        AccountNumber $account = null,
        $customerNr = '',
        $isOrg = false,
        $isVat = ''
    ) {
        $this->name = $name;
        $this->legalId = $legalId ?: new NullId;
        $this->account = $account ?: new NullAccount;
        $this->customerNr = $customerNr;
        $this->isOrg = $isOrg;
        if ($isOrg && $isVat) {
            $this->vatNr = str_replace(['-', '+'], '', "SE{$legalId}01");
        }
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get personal identifier
     *
     * @return Id
     */
    public function getId()
    {
        return $this->legalId;
    }

    /**
     * Get account
     *
     * @return AccountNumber
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Get customer number
     *
     * @return string
     */
    public function getCustomerNumber()
    {
        return $this->customerNr;
    }

    /**
     * Check if person is an organization
     *
     * @return boolean
     */
    public function isOrganization()
    {
        return $this->isOrg;
    }

    /**
     * Get swedish VAT number
     *
     * @see http://sv.wikipedia.org/wiki/Momsregistreringsnummer
     *
     * @return string VAT number
     */
    public function getVatNr()
    {
        return $this->vatNr;
    }
}

class NullAccount implements \byrokrat\banking\AccountNumber
{
    public function getBankName()
    {
        return '';
    }

    public function getRawNumber()
    {
        return '';
    }

    public function getNumber()
    {
        return '';
    }

    public function __toString()
    {
        return '';
    }

    public function getClearingNumber()
    {
        return '';
    }

    public function getClearingCheckDigit()
    {
        return '';
    }

    public function getSerialNumber()
    {
        return '';
    }

    public function getCheckDigit()
    {
        return '';
    }

    public function get16()
    {
        return '';
    }
}
