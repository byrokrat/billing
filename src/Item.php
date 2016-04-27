<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\amount\Amount;

/**
 * A charged item, it's unit cost and VAT rate
 */
interface Item
{
    /**
     * Get item description
     */
    public function getDescription(): string;

    /**
     * Get number of units
     */
    public function getNrOfUnits(): Amount;

    /**
     * Get cost per unit
     */
    public function getCostPerUnit(): Amount;

    /**
     * Get total cost of all units (VAT excluded)
     */
    public function getTotalUnitCost(): Amount;

    /**
     * Get VAT rate
     *
     * Note that 25% is represented as .25
     */
    public function getVatRate(): Amount;

    /**
     * Get total VAT cost for all units
     */
    public function getTotalVatCost(): Amount;

    /**
     * Get total item cost (VAT included)
     */
    public function getTotalCost(): Amount;
}
