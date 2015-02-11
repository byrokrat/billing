<?php

namespace byrokrat\billing;

use DateTime;
use DateInterval;
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
     * @var LegalPerson Seller
     */
    private $seller;

    /**
     * @var LegalPerson Buyer
     */
    private $buyer;

    /**
     * @var string Message to buyer
     */
    private $message;

    /**
     * @var Ocr Payment reference number
     */
    private $ocr;

    /**
     * @var Item[] List if charged items
     */
    private $items = [];

    /**
     * @var DateTime Creation date
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
     * @param string        $serial       Invoice serial number
     * @param LegalPerson   $seller       Seller object
     * @param LegalPerson   $buyer        Buyer object
     * @param string        $message      Invoice message
     * @param Ocr|null      $ocr          Payment reference number
     * @param Item[]        $items        Array of charged items
     * @param DateTime|null $billDate     Date of invoice creation
     * @param integer       $expiresAfter Nr of days before invoice expires
     * @param Amount|null   $deduction    Prepaid amound to deduct
     * @param string        $currency     3-letter ISO 4217 currency code indicating currency
     */
    public function __construct(
        $serial,
        LegalPerson $seller,
        LegalPerson $buyer,
        $message = '',
        Ocr $ocr = null,
        array $items = array(),
        DateTime $billDate = null,
        $expiresAfter = 30,
        Amount $deduction = null,
        $currency = 'SEK'
    ) {
        $this->serial = $serial;
        $this->seller = $seller;
        $this->buyer = $buyer;

        foreach ($items as $item) {
            $this->addItem($item);
        }

        $this->ocr = $ocr;
        $this->message = $message;
        $this->billDate = $billDate ?: new DateTime;
        $this->expiresAfter = $expiresAfter;
        $this->deduction = $deduction ?: new Amount('0');
        $this->currency = $currency;
    }

    /**
     * Get invoice serial number
     *
     * @return string
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * Get seller
     *
     * @return LegalPerson
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * Get buyer
     *
     * @return LegalPerson
     */
    public function getBuyer()
    {
        return $this->buyer;
    }

    /**
     * Get invoice message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get invoice reference number
     *
     * Returns null if no ocr was specified
     *
     * @return Ocr|null
     */
    public function getOcr()
    {
        return $this->ocr;
    }

    /**
     * Add item to invoice
     *
     * @param  Item $item
     * @return null
     */
    public function addItem(Item $item)
    {
        $this->items[] = $item;
    }

    /**
     * Get list of charged items
     *
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Get total VAT amount
     *
     * @return Amount
     */
    public function getTotalVatCost()
    {
        return array_reduce(
            $this->getItems(),
            function (Amount $carry, Item $item) {
                return $carry->add($item->getTotalVatCost());
            },
            new Amount('0')
        );
    }

    /**
     * Get total unit amount (VAT excluded)
     *
     * @return Amount
     */
    public function getTotalUnitCost()
    {
        return array_reduce(
            $this->getItems(),
            function (Amount $carry, Item $item) {
                return $carry->add($item->getTotalUnitCost());
            },
            new Amount('0')
        );
    }

    /**
     * Get charged amount (VAT included)
     *
     * @return Amount
     */
    public function getInvoiceTotal()
    {
        return $this->getTotalVatCost()
            ->add($this->getTotalUnitCost())
            ->subtract($this->getDeduction());
    }

    /**
     * Get unit cost totals for non-zero vat rates used in invoice
     *
     * @return Item[]
     */
    public function getVatTotals()
    {
        // TODO bättre namn tack!!
        $vatTotals = [];

        foreach ($this->getItems() as $item) {
            if ($item->getVatRate()->isPositive()) {
                $key = (string)$item->getVatRate();

                if (!array_key_exists($key, $vatTotals)) {
                    $vatTotals[$key] = new StandardItem(
                        '',
                        new Amount('1'),
                        new Amount('0'),
                        $item->getVatRate()
                    );
                }

                $vatTotals[$key] = new StandardItem(
                    $vatTotals[$key]->getDescription(),
                    $vatTotals[$key]->getNrOfUnits(),
                    $vatTotals[$key]->getCostPerUnit()->add($item->getTotalUnitCost()),
                    $vatTotals[$key]->getVatRate()
                );
            }
        }

        ksort($vatTotals);

        return array_values($vatTotals);
    }

    /**
     * Get date of invoice creation
     *
     * @return DateTime
     */
    public function getBillDate()
    {
        return $this->billDate;
    }

    /**
     * Get number of days before invoice expires
     *
     * @return integer
     */
    public function getExpiresAfter()
    {
        return $this->expiresAfter;
    }

    /**
     * Get date when invoice expires
     *
     * @return DateTime
     */
    public function getExpirationDate()
    {
        $expireDate = clone $this->billDate;
        $expireDate->add(new DateInterval("P{$this->getExpiresAfter()}D"));

        return $expireDate;
    }

    /**
     * Get prepaid amound to deduct
     *
     * @return Amount
     */
    public function getDeduction()
    {
        return $this->deduction;
    }

    /**
     * Get the 3-letter ISO 4217 currency code indicating the invoice currency
     *
     * @return string Currency code
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}
