<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\amount\Amount;

/**
 * Decorates a chargable item with sum calculations
 */
class ItemEnvelope implements Item
{
    /**
     * @var Item
     */
    private $item;

    /**
     * Pack item at construct
     */
    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    /**
     * Get packed item
     */
    public function getItem(): Item
    {
        return $this->item;
    }

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

    /**
     * Pass to decorated item
     */
    public function getDescription(): string
    {
        return $this->getItem()->getDescription();
    }

    /**
     * Pass to decorated item
     */
    public function getNrOfUnits(): int
    {
        return $this->getItem()->getNrOfUnits();
    }

    /**
     * Pass to decorated item
     */
    public function getCostPerUnit(): Amount
    {
        return $this->getItem()->getCostPerUnit();
    }

    /**
     * Pass to decorated item
     */
    public function getVatRate(): Amount
    {
        return $this->getItem()->getVatRate();
    }
}
