<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\amount\Amount;

/**
 * A billable item, it's unit cost and VAT rate
 */
interface Billable
{
    /**
     * Get description of the charged item
     */
    public function getBillingDescription(): string;

    /**
     * Get cost per billed unit
     */
    public function getCostPerUnit(): Amount;

    /**
     * Get number of billed units
     */
    public function getNrOfUnits(): int;

    /**
     * Get VAT rate applicable on billed item
     *
     * Note that 25% is represented as .25
     */
    public function getVatRate(): Amount;
}
