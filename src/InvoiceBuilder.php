<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\amount\Amount;

/**
 * Create complex invoices
 */
class InvoiceBuilder
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
     * @var OcrTools Tools for validating and creating ocr numbers
     */
    private $ocrTools;

    /**
     * @var ItemBasket Container of charged items
     */
    private $itemBasket;

    /**
     * @var \DateTimeImmutable Invoice creation date
     */
    private $billDate;

    /**
     * @var int Number of days before invoice expires
     */
    private $expiresAfter;

    /**
     * @var Amount Prepaid amound to deduct
     */
    private $deduction;

    /**
     * Reset values at construct
     */
    public function __construct(OcrTools $ocrTools = null)
    {
        $this->ocrTools = $ocrTools ?: new OcrTools;
        $this->reset();
    }

    /**
     * Reset builder values
     */
    public function reset(): self
    {
        $this->serial = null;
        $this->seller = null;
        $this->buyer = null;
        $this->ocr = '';
        $this->itemBasket = new ItemBasket;
        $this->billDate = null;
        $this->expiresAfter = 30;
        $this->deduction = null;
        $this->clearAttributes();
        return $this;
    }

    /**
     * Build invoice
     */
    public function buildInvoice(): Invoice
    {
        $invoice = new Invoice(
            $this->getSerial(),
            $this->getSeller(),
            $this->getBuyer(),
            $this->ocr,
            $this->itemBasket,
            $this->billDate ?: new \DateTimeImmutable,
            $this->expiresAfter,
            $this->deduction
        );

        foreach ($this->getAttributes() as $key => $value) {
            $invoice->setAttribute($key, $value);
        }

        return $invoice;
    }

    /**
     * Set invoice serial number
     */
    public function setSerial(string $serial): self
    {
        $this->serial = $serial;
        return $this;
    }

    /**
     * Get invoice serial number
     *
     * @throws Exception If serial is not set
     */
    public function getSerial(): string
    {
        if (isset($this->serial)) {
            return $this->serial;
        }
        throw new Exception("Unable to create invoice: serial not set");
    }

    /**
     * Set seller
     */
    public function setSeller(AgentInterface $seller): self
    {
        $this->seller = $seller;
        return $this;
    }

    /**
     * Get seller
     *
     * @throws Exception If seller is not set
     */
    public function getSeller(): AgentInterface
    {
        if (isset($this->seller)) {
            return $this->seller;
        }
        throw new Exception("Unable to create Invoice: seller not set");
    }

    /**
     * Set buyer
     */
    public function setBuyer(AgentInterface $buyer): self
    {
        $this->buyer = $buyer;
        return $this;
    }

    /**
     * Get buyer
     *
     * @throws Exception If buyer is not set
     */
    public function getBuyer(): AgentInterface
    {
        if (isset($this->buyer)) {
            return $this->buyer;
        }
        throw new Exception("Unable to create Invoice: buyer not set");
    }

    /**
     * Set invoice reference number
     */
    public function setOcr(string $ocr): self
    {
        $this->ocrTools->validate($ocr);
        $this->ocr = $ocr;
        return $this;
    }

    /**
     * Generate invoice reference number from serial number
     */
    public function generateOcr(): self
    {
        $this->ocr = $this->ocrTools->create($this->getSerial());
        return $this;
    }

    /**
     * Add billable to invoice
     */
    public function addItem(Billable $billable): self
    {
        $this->itemBasket->addItem(new ItemEnvelope($billable));
        return $this;
    }

    /**
     * Set date of invoice creation
     */
    public function setBillDate(\DateTimeImmutable $date): self
    {
        $this->billDate = $date;
        return $this;
    }

    /**
     * Set number of days before invoice expires
     */
    public function setExpiresAfter(int $nrOfDays): self
    {
        $this->expiresAfter = $nrOfDays;
        return $this;
    }

    /**
     * Set deduction (amount prepaid)
     */
    public function setDeduction(Amount $deduction): self
    {
        $this->deduction = $deduction;
        return $this;
    }
}
