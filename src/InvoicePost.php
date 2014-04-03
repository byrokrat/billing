<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\billing;

use ledgr\amount\Amount;

/**
 * An InvoicePost reresents a charged item, it's unit cost and VAT rate
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class InvoicePost
{
    /**
     * @var string Post description
     */
    private $description;

    /**
     * @var Amount Number of units
     */
    private $units;

    /**
     * @var Amount Cost per unit
     */
    private $unitCost;

    /**
     * @var Amount VAT rate
     */
    private $vat;

    /**
     * Constructor
     *
     * @param string $desc     Post description
     * @param Amount $units    Number of units
     * @param Amount $unitCost Cost per unit
     * @param Amount $vat      VAT rate, note that for 25% the value should be .25
     */
    public function __construct($desc, Amount $units, Amount $unitCost, Amount $vat = null)
    {
        $this->description = (string)$desc;
        $this->units = $units;
        $this->unitCost = $unitCost;
        $this->vat = $vat ?: new Amount('0');
    }

    /**
     * Get post description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get number of units
     *
     * @return Amount
     */
    public function getNrOfUnits()
    {
        return $this->units;
    }

    /**
     * Get cost per unit
     *
     * @return Amount
     */
    public function getUnitCost()
    {
        return $this->unitCost;
    }

    /**
     * Get total post cost
     *
     * @return Amount
     */
    public function getUnitTotal()
    {
        $cost = clone $this->getUnitCost();
        return $cost->multiplyWith($this->getNrOfUnits());
    }

    /**
     * Get VAT rate
     *
     * @return Amount
     */
    public function getVatRate()
    {
        return $this->vat;
    }

    /**
     * Get total VAT for post
     *
     * @return Amount
     */
    public function getVatTotal()
    {
        return $this->getUnitTotal()->multiplyWith($this->getVatRate());
    }
}
