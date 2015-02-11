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
     * @var InvoicePost[] List if invoice posts
     */
    private $posts = [];

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
     * @param Ocr           $ocr          Payment reference number
     * @param InvoicePost[] $posts        Array of InvoicePost objects
     * @param DateTime      $billDate     Date of invoice creation
     * @param integer       $expiresAfter Nr of days before invoice expires
     * @param Amount        $deduction    Prepaid amound to deduct
     * @param string        $currency     3-letter ISO 4217 currency code indicating currency
     */
    public function __construct(
        $serial,
        LegalPerson $seller,
        LegalPerson $buyer,
        $message = '',
        Ocr $ocr = null,
        array $posts = array(),
        DateTime $billDate = null,
        $expiresAfter = 30,
        Amount $deduction = null,
        $currency = 'SEK'
    ) {
        $this->serial = $serial;
        $this->seller = $seller;
        $this->buyer = $buyer;

        foreach ($posts as $post) {
            $this->addPost($post);
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
     * Add post to invoice
     *
     * @param  InvoicePost $post
     * @return null
     */
    public function addPost(InvoicePost $post)
    {
        $this->posts[] = $post;
    }

    /**
     * Get list of invoice posts
     *
     * @return InvoicePost[]
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Get total VAT amount
     *
     * @return Amount
     */
    public function getVatTotal()
    {
        return array_reduce(
            $this->getPosts(),
            function (Amount $carry, InvoicePost $post) {
                return $carry->add($post->getVatTotal());
            },
            new Amount('0')
        );
    }

    /**
     * Get total unit amount (VAT excluded)
     *
     * @return Amount
     */
    public function getUnitTotal()
    {
        return array_reduce(
            $this->getPosts(),
            function (Amount $carry, InvoicePost $post) {
                return $carry->add($post->getUnitTotal());
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
        return $this->getVatTotal()
            ->add($this->getUnitTotal())
            ->subtract($this->getDeduction());
    }

    /**
     * Get unit cost totals for non-zero vat rates used in invoice
     *
     * @return InvoicePost[]
     */
    public function getVatTotals()
    {
        $vatTotals = [];

        foreach ($this->getPosts() as $post) {
            if ($post->getVatRate()->isPositive()) {
                $key = (string)$post->getVatRate();

                if (!array_key_exists($key, $vatTotals)) {
                    $vatTotals[$key] = new InvoicePost(
                        '',
                        new Amount('1'),
                        new Amount('0'),
                        $post->getVatRate()
                    );
                }

                $vatTotals[$key] = new InvoicePost(
                    $vatTotals[$key]->getDescription(),
                    $vatTotals[$key]->getNrOfUnits(),
                    $vatTotals[$key]->getUnitCost()->add($post->getUnitTotal()),
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
