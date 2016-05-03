<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\amount\Amount;

/**
 * Decorates a chargable billable with sum calculations
 */
class ItemEnvelope implements Billable
{
    /**
     * @var Billable
     */
    private $billable;

    /**
     * Pack billable at construct
     */
    public function __construct(Billable $billable)
    {
        $this->billable = $billable;
    }

    /**
     * Get packed billable
     */
    public function getBillable(): Billable
    {
        return $this->billable;
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
     * Get total cost (VAT included)
     */
    public function getTotalCost(): Amount
    {
        return $this->getTotalUnitCost()->add($this->getTotalVatCost());
    }

    /**
     * Get classname of currency used in billable
     */
    public function getCurrencyClassname(): string
    {
        return get_class($this->getCostPerUnit());
    }

    /**
     * Pass to decorated billable
     */
    public function getBillingDescription(): string
    {
        return $this->getBillable()->getBillingDescription();
    }

    /**
     * Pass to decorated billable
     */
    public function getCostPerUnit(): Amount
    {
        return $this->getBillable()->getCostPerUnit();
    }

    /**
     * Pass to decorated billable
     */
    public function getNrOfUnits(): int
    {
        return $this->getBillable()->getNrOfUnits();
    }

    /**
     * Pass to decorated billable
     */
    public function getVatRate(): float
    {
        return $this->getBillable()->getVatRate();
    }
}
