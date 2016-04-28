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
     * @var ItemEnvelope[] List if charged items
     */
    private $items = [];

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
     * @var string 3-letter ISO 4217 currency code indicating currency
     */
    private $currency;

    /**
     * Construct invoice
     *
     * @param string         $serial       Invoice serial number
     * @param Seller         $seller       Registered seller
     * @param Buyer          $buyer        Registered buyer
     * @param string         $message      Invoice message
     * @param string         $ocr          Payment reference number
     * @param ItemEnvelope[] $items        Array of charged items
     * @param \DateTime|null $billDate     Date of invoice creation
     * @param integer        $expiresAfter Nr of days before invoice expires
     * @param Amount|null    $deduction    Prepaid amound to deduct
     * @param string         $currency     3-letter ISO 4217 currency code indicating currency
     */
    public function __construct(
        string $serial,
        Seller $seller,
        Buyer $buyer,
        string $message = '',
        string $ocr = '',
        array $items = array(),
        \DateTime $billDate = null,
        int $expiresAfter = 30,
        Amount $deduction = null,
        string $currency = 'SEK'
    ) {
        $this->serial = $serial;
        $this->seller = $seller;
        $this->buyer = $buyer;

        foreach ($items as $item) {
            $this->addItem($item);
        }

        $this->ocr = $ocr;
        $this->message = $message;
        $this->billDate = $billDate ?: new \DateTime;
        $this->expiresAfter = $expiresAfter;
        $this->deduction = $deduction ?: new Amount('0');
        $this->currency = $currency;
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
     * Add item to invoice
     *
     * @return void
     */
    public function addItem(ItemEnvelope $item)
    {
        $this->items[] = $item;
    }

    /**
     * Get list of charged items
     *
     * @return ItemEnvelope[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Get total cost of all items (VAT excluded)
     */
    public function getTotalUnitCost(): Amount
    {
        return array_reduce(
            $this->getItems(),
            function (Amount $carry, ItemEnvelope $item) {
                return $carry->add($item->getTotalUnitCost());
            },
            new Amount('0')
        );
    }

    /**
     * Get total VAT cost for all items
     */
    public function getTotalVatCost(): Amount
    {
        return array_reduce(
            $this->getItems(),
            function (Amount $carry, ItemEnvelope $item) {
                return $carry->add($item->getTotalVatCost());
            },
            new Amount('0')
        );
    }

    /**
     * Get charged amount (VAT included)
     */
    public function getTotalCost(): Amount
    {
        return $this->getTotalVatCost()
            ->add($this->getTotalUnitCost())
            ->subtract($this->getDeduction());
    }

    /**
     * Get charged vat amounts for non-zero vat rates
     *
     * @return Item[]
     */
    public function getVatRates(): array
    {
        $rates = [];

        foreach ($this->getItems() as $item) {
            if ($item->getVatRate()->isPositive()) {
                $key = (string)$item->getVatRate();

                if (!array_key_exists($key, $rates)) {
                    $rates[$key] = new SimpleItem(
                        '',
                        new Amount('0'),
                        1,
                        $item->getVatRate()
                    );
                }

                $rates[$key] = new SimpleItem(
                    $rates[$key]->getBillingDescription(),
                    $rates[$key]->getCostPerUnit()->add($item->getTotalUnitCost()),
                    $rates[$key]->getNrOfUnits(),
                    $rates[$key]->getVatRate()
                );
            }
        }

        ksort($rates);

        return array_values($rates);
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
        return $this->deduction;
    }

    /**
     * Get the 3-letter ISO 4217 currency code indicating the invoice currency
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }
}
