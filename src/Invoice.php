<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\amount\Amount;

/**
 * Generic invoice container object
 */
class Invoice
{
    /**
     * @var string Invoice serial number
     */
    private $serial;

    /**
     * @var Seller Registered seller
     */
    private $seller;

    /**
     * @var Buyer Registered buyer
     */
    private $buyer;

    /**
     * @var string Message to buyer
     */
    private $message;

    /**
     * @var string Payment reference number
     */
    private $ocr;

    /**
     * @var ItemBasket Container for charged items
     */
    private $itemBasket = [];

    /**
     * @var \DateTime Creation date
     */
    private $billDate;

    /**
     * @var integer Number of days before invoice expires
     */
    private $expiresAfter;

    /**
     * @var Amount Prepaid amound to deduct
     */
    private $deduction;

    /**
     * Construct invoice
     *
     * @param string     $serial       Invoice serial number
     * @param Seller     $seller       Registered seller
     * @param Buyer      $buyer        Registered buyer
     * @param string     $message      Invoice message
     * @param string     $ocr          Payment reference number
     * @param ItemBasket $itemBasket   Container for charged items
     * @param \DateTime  $billDate     Date of invoice creation
     * @param integer    $expiresAfter Nr of days before invoice expires
     * @param Amount     $deduction    Prepaid amound to deduct
     */
    public function __construct(
        string $serial,
        Seller $seller,
        Buyer $buyer,
        string $message = '',
        string $ocr = '',
        ItemBasket $itemBasket = null,
        \DateTime $billDate = null,
        int $expiresAfter = 30,
        Amount $deduction = null
    ) {
        $this->serial = $serial;
        $this->seller = $seller;
        $this->buyer = $buyer;
        $this->itemBasket = $itemBasket;
        $this->ocr = $ocr;
        $this->message = $message;
        $this->billDate = $billDate ?: new \DateTime;
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
    public function getSeller(): Seller
    {
        return $this->seller;
    }

    /**
     * Get buyer
     */
    public function getBuyer(): Buyer
    {
        return $this->buyer;
    }

    /**
     * Get invoice message
     */
    public function getMessage(): string
    {
        return $this->message;
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
    public function getBillDate(): \DateTime
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
    public function getExpirationDate(): \DateTime
    {
        $expireDate = clone $this->billDate;
        $expireDate->add(new \DateInterval("P{$this->getExpiresAfter()}D"));

        return $expireDate;
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
