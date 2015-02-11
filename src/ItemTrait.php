<?php

namespace byrokrat\billing;

use byrokrat\amount\Amount;

/**
 * Helper that defines totals calculations for item implementations
 */
trait ItemTrait
{
    /**
     * Get number of units
     *
     * @return Amount
     */
    abstract public function getNrOfUnits();

    /**
     * Get cost per unit
     *
     * @return Amount
     */
    abstract public function getCostPerUnit();

    /**
     * Get VAT rate
     *
     * @return Amount
     */
    abstract public function getVatRate();

    /**
     * Get total cost of all units (VAT excluded)
     *
     * @return Amount
     */
    public function getTotalUnitCost()
    {
        return $this->getCostPerUnit()->multiplyWith($this->getNrOfUnits());
    }

    /**
     * Get total VAT cost for all units
     *
     * @return Amount
     */
    public function getTotalVatCost()
    {
        return $this->getTotalUnitCost()->multiplyWith($this->getVatRate());
    }

    /**
     * Get total item cost (VAT included)
     *
     * @return Amount
     */
    public function getTotalCost()
    {
        return $this->getTotalUnitCost()->add($this->getTotalVatCost());
    }
}
