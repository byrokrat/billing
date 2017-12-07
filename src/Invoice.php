<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\amount\Amount;

/**
 * Generic invoice container object
 */
class Invoice
{
    use AttributesTrait;

    /**
     * @var string Invoice serial number
     */
    private $serial;

    /**
     * @var AgentInterface Registered seller
     */
    private $seller;

    /**
     * @var AgentInterface Registered buyer
     */
    private $buyer;

    /**
     * @var string Payment reference number
     */
    private $ocr;

    /**
     * @var ItemBasket Container for charged items
     */
    private $itemBasket;

    /**
     * @var \DateTimeInterface Creation date
     */
    private $billDate;

    /**
     * @var integer Number of days before invoice expires
     */
    private $expiresAfter;

    /**
     * @var ?Amount Prepaid amound to deduct
     */
    private $deduction;

    /**
     * Load values at construct
     *
     * @param string             $serial       Invoice serial number
     * @param AgentInterface     $seller       Registered seller
     * @param AgentInterface     $buyer        Registered buyer
     * @param string             $ocr          Payment reference number
     * @param ItemBasket         $itemBasket   Container for charged items
     * @param \DateTimeInterface $billDate     Date of invoice creation
     * @param integer            $expiresAfter Nr of days before invoice expires
     * @param Amount             $deduction    Prepaid amound to deduct
     */
    public function __construct(
        string $serial,
        AgentInterface $seller,
        AgentInterface $buyer,
        string $ocr,
        ItemBasket $itemBasket,
        \DateTimeInterface $billDate,
        int $expiresAfter,
        ?Amount $deduction = null
    ) {
        $this->serial = $serial;
        $this->seller = $seller;
        $this->buyer = $buyer;
        $this->ocr = $ocr;
        $this->itemBasket = $itemBasket;
        $this->billDate = $billDate;
        $this->expiresAfter = $expiresAfter;
        $this->deduction = $deduction;
    }

    /**
     * Get invoice serial number
     */
    public function getSerial(): string
    {
        return $this->serial;
    }

    /**
     * Get seller
     */
    public function getSeller(): AgentInterface
    {
        return $this->seller;
    }

    /**
     * Get buyer
     */
    public function getBuyer(): AgentInterface
    {
        return $this->buyer;
    }

    /**
     * Get invoice reference number
     */
    public function getOcr(): string
    {
        return $this->ocr;
    }

    /**
     * Get item container
     */
    public function getItems(): ItemBasket
    {
        return $this->itemBasket;
    }

    /**
     * Get charged amount (including VAT and deduction)
     */
    public function getInvoiceTotal(): Amount
    {
        return $this->getItems()->getTotalCost()->subtract($this->getDeduction());
    }

    /**
     * Get date of invoice creation
     */
    public function getBillDate(): \DateTimeInterface
    {
        return $this->billDate;
    }

    /**
     * Get number of days before invoice expires
     */
    public function getExpiresAfter(): int
    {
        return $this->expiresAfter;
    }

    /**
     * Get date when invoice expires
     */
    public function getExpirationDate(): \DateTimeInterface
    {
        return new \DateTimeImmutable(
            '@' . ($this->billDate->getTimestamp() + $this->getExpiresAfter() * 24 * 60 * 60)
        );
    }

    /**
     * Get prepaid amound to deduct
     */
    public function getDeduction(): Amount
    {
        if (!isset($this->deduction)) {
            return $this->itemBasket->createCurrencyObject('0');
        }

        return $this->deduction;
    }
}
