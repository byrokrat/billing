<?php

namespace byrokrat\billing;

use byrokrat\amount\Amount;

/**
 * A charged item, it's unit cost and VAT rate
 */
interface Item
{
    /**
     * Get item description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Get number of units
     *
     * @return Amount
     */
    public function getNrOfUnits();

    /**
     * Get cost per unit
     *
     * @return Amount
     */
    public function getCostPerUnit();

    /**
     * Get total cost of all units (VAT excluded)
     *
     * @return Amount
     */
    public function getTotalUnitCost();

    /**
     * Get VAT rate
     *
     * Note that 25% is represented as .25
     *
     * @return Amount
     */
    public function getVatRate();

    /**
     * Get total VAT cost for all units
     *
     * @return Amount
     */
    public function getTotalVatCost();

    /**
     * Get total item cost (VAT included)
     *
     * @return Amount
     */
    public function getTotalCost();
}
