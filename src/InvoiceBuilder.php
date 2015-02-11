<?php

namespace byrokrat\billing;

use DateTime;
use byrokrat\amount\Amount;

/**
 * Create complex invoices
 */
class InvoiceBuilder
{
    /**
     * @var string Invoice serial number
     */
    private $serial;

    /**
     * @var Ocr Payment reference number
     */
    private $ocr;

    /**
     * @var boolean Flag if ocr may be generated from serial
     */
    private $generateOcr = false;

    /**
     * @var LegalPerson Seller
     */
    private $seller;

    /**
     * @var LegalPerson Buyer
     */
    private $buyer;

    /**
     * @var DateTime Invoice creation date
     */
    private $billDate;

    /**
     * @var integer Number of days before invoice expires
     */
    private $expiresAfter = 30;

    /**
     * @var Amount Deduction of total cost
     */
    private $deduction = null;

    /**
     * @var string Message to buyer
     */
    private $message = '';

    /**
     * @var array Collection of InvoicePost objects
     */
    private $posts = [];

    /**
     * @var string 3-letter ISO 4217 currency code indicating the currency to use
     */
    private $currency = 'SEK';

    /**
     * Create invoice
     *
     * @return Invoice
     */
    public function getInvoice()
    {
        return new Invoice(
            $this->getSerial(),
            $this->getSeller(),
            $this->getBuyer(),
            $this->message,
            $this->getOcr(),
            $this->posts,
            $this->getBillDate(),
            $this->expiresAfter,
            $this->deduction,
            $this->currency
        );
    }

    /**
     * Reset builder values
     *
     * @return InvoiceBuilder Instance for chaining
     */
    public function reset()
    {
        unset($this->serial);
        unset($this->ocr);
        $this->generateOcr = false;
        unset($this->seller);
        unset($this->buyer);
        unset($this->billDate);
        $this->expiresAfter = 30;
        $this->deduction = null;
        $this->message = '';
        $this->posts = [];
        $this->currency = 'SEK';

        return $this;
    }

    /**
     * Set serial
     *
     * @param  string         $serial
     * @return InvoiceBuilder Instance for chaining
     */
    public function setSerial($serial)
    {
        $this->serial = $serial;
        return $this;
    }

    /**
     * Get invoice serial number
     *
     * @return string
     * @throws RuntimeException If serial is not set
     */
    public function getSerial()
    {
        if (isset($this->serial)) {
            return $this->serial;
        }

        throw new RuntimeException("Unable to create Invoice: serial not set.");
    }

    /**
     * Set seller
     *
     * @param  LegalPerson    $seller
     * @return InvoiceBuilder Instance for chaining
     */
    public function setSeller(LegalPerson $seller)
    {
        $this->seller = $seller;
        return $this;
    }

    /**
     * Get seller
     *
     * @return LegalPerson
     * @throws RuntimeException If seller is not set
     */
    public function getSeller()
    {
        if (isset($this->seller)) {
            return $this->seller;
        }

        throw new RuntimeException("Unable to create Invoice: seller not set.");
    }

    /**
     * Set buyer
     *
     * @param  LegalPerson    $buyer
     * @return InvoiceBuilder Instance for chaining
     */
    public function setBuyer(LegalPerson $buyer)
    {
        $this->buyer = $buyer;
        return $this;
    }

    /**
     * Get buyer
     *
     * @return LegalPerson
     * @throws RuntimeException If buyer is not set
     */
    public function getBuyer()
    {
        if (isset($this->buyer)) {
            return $this->buyer;
        }

        throw new RuntimeException("Unable to create Invoice: buyer not set.");
    }

    /**
     * Set date of invoice creation
     *
     * @param  DateTime       $date
     * @return InvoiceBuilder Instance for chaining
     */
    public function setBillDate(DateTime $date)
    {
        $this->billDate = $date;
        return $this;
    }

    /**
     * Get date of invoice creation
     *
     * @return DateTime
     */
    public function getBillDate()
    {
        return isset($this->billDate) ? $this->billDate : new DateTime;
    }

    /**
     * Set if ocr may be generated from serial
     *
     * @param  boolean        $generateOcr
     * @return InvoiceBuilder Instance for chaining
     */
    public function setGenerateOcr($generateOcr = true)
    {
        $this->generateOcr = $generateOcr;
        return $this;
    }

    /**
     * Set invoice reference number
     *
     * @param  Ocr            $ocr
     * @return InvoiceBuilder Instance for chaining
     */
    public function setOcr(Ocr $ocr)
    {
        $this->ocr = $ocr;
        return $this;
    }

    /**
     * Get invoice reference number
     *
     * @return Ocr|null Null if ocr is not set
     */
    public function getOcr()
    {
        if (isset($this->ocr)) {
            return $this->ocr;
        }

        if ($this->generateOcr) {
            return (new OcrFactory)->createOcr($this->getSerial());
        }
    }

    /**
     * Set invoice message
     *
     * @param  string         $message
     * @return InvoiceBuilder Instance for chaining
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Set number of days before invoice expires
     *
     * @param  int            $term Number of days
     * @return InvoiceBuilder Instance for chaining
     */
    public function setExpiresAfter($term)
    {
        $this->expiresAfter = $term;
        return $this;
    }

    /**
     * Set deduction (amount prepaid)
     *
     * @param  Amount         $deduction
     * @return InvoiceBuilder Instance for chaining
     */
    public function setDeduction(Amount $deduction)
    {
        $this->deduction = $deduction;
        return $this;
    }

    /**
     * Set the 3-letter ISO 4217 currency code indicating the invoice currency
     *
     * @param  string $currency Currency code
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * Add post to invoice
     *
     * @param  InvoicePost    $post
     * @return InvoiceBuilder Instance for chaining
     */
    public function addPost(InvoicePost $post)
    {
        $this->posts[] = $post;
        return $this;
    }
}
