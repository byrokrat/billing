<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\amount\Amount;

/**
 * Helper that defines totals calculations for item implementations
 */
trait ItemHelper
{
    /**
     * Get number of units
     */
    abstract public function getNrOfUnits(): Amount;

    /**
     * Get cost per unit
     */
    abstract public function getCostPerUnit(): Amount;

    /**
     * Get VAT rate
     */
    abstract public function getVatRate(): Amount;

    /**
     * Get total cost of all units (VAT excluded)
     */
    public function getTotalUnitCost(): Amount
    {
        return $this->getCostPerUnit()->multiplyWith($this->getNrOfUnits());
    }

    /**
     * Get total VAT cost for all units
     */
    public function getTotalVatCost(): Amount
    {
        return $this->getTotalUnitCost()->multiplyWith($this->getVatRate());
    }

    /**
     * Get total item cost (VAT included)
     */
    public function getTotalCost(): Amount
    {
        return $this->getTotalUnitCost()->add($this->getTotalVatCost());
    }
}
