<?php

namespace byrokrat\billing;

use byrokrat\amount\Amount;

/**
 * Basic item implementation
 */
class StandardItem implements Item
{
    use ItemHelper;

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
     * Load item data
     *
     * @param string $description Post description
     * @param Amount $units       Number of units
     * @param Amount $unitCost    Cost per unit
     * @param Amount $vat         VAT rate, note that for 25% the value should be .25
     */
    public function __construct($description, Amount $units, Amount $unitCost, Amount $vat = null)
    {
        $this->description = $description;
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
    public function getCostPerUnit()
    {
        return $this->unitCost;
    }

    /**
     * Get VAT rate
     *
     * {@inheritdoc}
     *
     * @return Amount
     */
    public function getVatRate()
    {
        return $this->vat;
    }
}
