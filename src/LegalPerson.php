<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\billing;

use ledgr\id\IdInterface;
use ledgr\id\NullId;
use ledgr\banking\BankAccountInterface;
use ledgr\banking\NullAccount;

/**
 * A LegalPerson is a container for id, accounts and more
 *
 * Legal persons are of two kinds: natural persons (people) and juridical persons,
 * groups of people, such as corporations, which are treated by law as if they were persons.
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class LegalPerson
{
    /**
     * @var string Name of this legal person
     */
    private $name;

    /**
     * @var IdInterface Personal identifier
     */
    private $id;

    /**
     * @var BankAccountInterface Account registered with person
     */
    private $account;

    /**
     * @var string Optional customer number for billing
     */
    private $customerNr;

    /**
     * @var boolean Flag if this person is a corporation
     */
    private $corporation = false;

    /**
     * @var string Optional VAT number for corporations
     */
    private $vatNr = '';

    /**
     * Construct legal person container
     *
     * @param string               $name        Name of legal person
     * @param IdInterface          $id          Peronal identifier
     * @param BankAccountInterface $account     Account number
     * @param string               $customerNr  Customer number for billing
     * @param boolean              $corporation Flag if person is a corporation
     * @param boolean              $vat         Flag if corporation is registered for VAT
     */
    public function __construct(
        $name,
        IdInterface $id = null,
        BankAccountInterface $account = null,
        $customerNr = '',
        $corporation = false,
        $vat = false
    ) {
        $this->name = (string)$name;
        $this->id = $id;
        $this->account = $account;
        $this->customerNr = (string)$customerNr;
        $this->corporation = (bool)$corporation;
        if ($corporation && $vat) {
            $this->vatNr = str_replace(array('-', '+'), '', "SE{$id}01");
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
     * @return IdInterface
     */
    public function getId()
    {
        return $this->id ?: new NullId;
    }

    /**
     * Get account
     *
     * @return BankAccountInterface
     */
    public function getAccount()
    {
        return $this->account ?: new NullAccount;
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
     * Check if person is corporation
     *
     * @return boolean
     */
    public function isCorporation()
    {
        return $this->corporation;
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
