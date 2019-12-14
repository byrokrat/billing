<?php

namespace byrokrat\paperinvoice;

use byrokrat\paperinvoice\I18n\Swedish as DefaultI18n;
use byrokrat\billing\Invoice;
use byrokrat\amount\Currency;

/**
 * Pdf representation of invoice
 */
class PaperInvoice extends FPDF
{
    /**
     * Bottom margin of document pages
     */
    const MARGIN_BOTTOM = 20;

    /**
     * @var Invoice Object to print
     */
    private $invoice;

    /**
     * @var I18nInterface Translator and formatter
     */
    private $i18n;

    /**
     * Contact information fields. Alter values to print on invoice
     */
    public $buyerAddress = '';
    public $sellerAddress = '';
    public $sellerPhone = '';
    public $sellerMail = '';

    public $sellerAccount;
    public $sellerId;

    /**
     * @param Invoice       $invoice Invoice object to print
     * @param I18nInterface $i18n    Translator and formatter
     */
    public function __construct(Invoice $invoice, I18nInterface $i18n = null)
    {
        parent::__construct(10);
        $this->invoice = $invoice;
        $this->i18n = $i18n ?: new DefaultI18n;
        $this->setTitle($this->getInvoiceTitle());
        $this->setCreator($invoice->getSeller()->getName());
    }

    public function getInvoiceTitle()
    {
        return implode(
            ' ',
            array(
                $this->i18n->translate('INVOICE'),
                $this->invoice->getSerial(),
                $this->i18n->formateDate($this->invoice->getBillDate()),
                $this->invoice->getSeller()->getName()
            )
        );
    }

    protected function draw()
    {
        $this->AddPage();
        $this->drawInvoiceInfo();
        $this->drawVatRates();
        $this->drawTotals();
        $this->drawMessage();
        $this->drawPostHeaders(63);
        $this->drawPosts();
    }

    private function drawInvoiceInfo()
    {
        $info = array(
            $this->i18n->translate('Invoice date')   => $this->i18n->formateDate($this->invoice->getBillDate()),
            $this->i18n->translate('Invoice number') => $this->invoice->getSerial(),
            $this->i18n->translate('Reference')      => $this->invoice->getOCR(),
            $this->i18n->translate('Payment term')   => $this->invoice->getAttribute('payment-term', '30') . $this->i18n->translate(' days'),
            $this->i18n->translate('Expiry date')    => $this->i18n->formateDate($this->invoice->getExpirationDate())
        );

        $this->SetFont('Helvetica', '', 12);

        $this->setXY(12, 35);
        $this->MultiCell(0, 5, implode("\n", array_keys($info)));

        $this->setXY(50, 35);
        $this->MultiCell(0, 5, implode("\n", $info));

        $buyer = array(
            $this->invoice->getBuyer()->getName(),
            $this->buyerAddress
        );

        $this->setXY(120, 35);
        $this->MultiCell(0, 5, implode("\n", $buyer));
    }

    private function drawPostHeaders($y)
    {
        $this->Line(10, $y, 200, $y);

        $this->SetFont('Helvetica', '', 10);
        $y += 3;

        $this->writeXY(105, $y, 5, $this->i18n->translate('VAT rate'));
        $this->writeXY(125, $y, 5, $this->i18n->translate('Amount'));
        $this->writeXY(137, $y, 5, $this->i18n->translate('Unit cost'));
        $this->writeXY(165, $y, 5, $this->i18n->translate('Total'));
        $this->ln(7);
    }

    private function drawPosts()
    {
        $this->SetFont('Courier', '', 11);

        foreach ($this->invoice->getItems() as $item) {
            $this->setX(105);
            $this->write(5, $this->i18n->formatPercentage($item->getVatRate()));
            $this->setX(125);
            $this->write(5, $this->i18n->formatUnits($item->getNrOfUnits()));
            $this->setX(137);
            $this->write(5, $this->i18n->formatCurrency($item->getCostPerUnit()));
            $this->setX(165);
            $this->write(5, $this->i18n->formatCurrency($item->getTotalUnitCost()));

            $this->setX(10);
            $this->MultiCell(90, 4, $item->getBillingDescription());

            $this->ln(5);

            $margin = 275 + ((self::MARGIN_BOTTOM + 80) * -1);

            if ($this->getY()+20 > $margin && $this->PagesAdded() == 1) {
                $this->Write(5, $this->i18n->translate('Continues on next page..'));
                $this->AddPage();
                $this->SetAutoPageBreak(true, 45);
                $this->SetFont('Courier', '', 11);
            }
        }
    }

    private function drawVatRates()
    {
        $x = 10;
        $y = (self::MARGIN_BOTTOM + 80) * -1;

        $rates = $this->invoice->getItems()->getVatRates();

        if (count($rates) > 4) {
            $msg = "PaperInvoice can handle a maximum of 4 different VAT rates in a single invoice.";
            throw new Exception($msg);
        }

        foreach ($rates as $rate => $rateData) {
            $data = array(
                $this->i18n->translate('VAT rate') => $this->i18n->formatPercentage($rate),
                $this->i18n->translate('Basis')    => $this->i18n->formatCurrency($rateData['unit_total']),
                $this->i18n->translate('To pay')   => $this->i18n->formatCurrency($rateData['vat_total'])
            );

            $this->SetFont('Helvetica', '', 10);
            $this->setXY($x, $y);
            $this->MultiCell(0, 5, implode("\n", array_keys($data)));

            $this->SetFont('Helvetica', '', 12);
            $this->setXY($x + 20, $y);
            $this->MultiCell(0, 5, implode("\n", $data));

            $x += 48;
        }
    }

    private function drawTotals()
    {
        $rows = array(
            (self::MARGIN_BOTTOM + 56) * -1,
            (self::MARGIN_BOTTOM + 49) * -1
        );

        $cols = array(15, 60, 100, 160);

        $this->SetFont('Helvetica', '', 12);

        $this->writeXY($cols[0], $rows[0], 5, $this->i18n->translate('Total ex. VAT'));
        $this->writeXY($cols[1], $rows[0], 5, $this->i18n->translate('Total VAT'));

        $deduction = $this->invoice->getDeduction();

        if ($deduction->isPositive()) {
            $this->writeXY($cols[2], $rows[0], 5, $this->i18n->translate('Deduction'));
            $this->SetFont('Helvetica', '', 14);
            $this->writeXY($cols[2], $rows[1], 5, $this->i18n->formatCurrency($deduction));
        }

        $this->SetFont('Helvetica', '', 14);

        $this->writeXY($cols[0], $rows[1], 5, $this->i18n->formatCurrency($this->invoice->getItems()->getTotalUnitCost()));
        $this->writeXY($cols[1], $rows[1], 5, $this->i18n->formatCurrency($this->invoice->getItems()->getTotalVatCost()));

        $this->SetFont('Helvetica', 'B', 14);

        $totalCost = $this->invoice->getItems()->getTotalCost();

        $currency = 'SEK';

        if ($totalCost instanceof Currency) {
            $currency = $totalCost->getCurrencyCode();
        }

        $this->writeXY($cols[3], $rows[0], 5, $this->i18n->translate('To pay'));
        $this->writeXY($cols[3], $rows[1], 5, $this->i18n->formatCurrencySymbol($totalCost, $currency));

        $this->Rect(10, 297-self::MARGIN_BOTTOM-60, 190, 20);
        $this->Rect(11, 297-self::MARGIN_BOTTOM-59, 188, 18);
    }

    private function drawMessage()
    {
        $message = $this->invoice->getAttribute('message', '');

        if ($this->invoice->getAttribute('tax-registered')) {
            $message .= ' ' . $this->i18n->translate('Tax registered');
        }

        $this->SetFont('Helvetica', '', 12);
        $this->setXY(10, (self::MARGIN_BOTTOM + 37) * -1);
        $this->MultiCell(0, 5, trim($message));
    }

    public function header()
    {
        $this->setFont('Helvetica','B',28);
        $this->writeXY(10, 10, 10, $this->invoice->getSeller()->getName());

        $x = floor(-12 - $this->GetStringWidth($this->i18n->translate('INVOICE')));

        $this->setFont('Helvetica', '', 18);
        $this->writeXY($x, 10, 10, $this->i18n->translate('INVOICE'));

        $this->setFont('Helvetica', '', 10);
        $this->writeXY($x, 19, 5, $this->i18n->translate('Page ') . $this->PaginationStr($this->i18n->translate(' of ')));

        if ($this->PagesAdded() > 1) {
            $this->drawPostHeaders(29);
        }
    }

    public function footer()
    {
        $rows = array(
            297-self::MARGIN_BOTTOM-19,
            (self::MARGIN_BOTTOM + 16) * -1,
            (self::MARGIN_BOTTOM + 11) * -1,
            (self::MARGIN_BOTTOM + 5) * -1,
            self::MARGIN_BOTTOM * -1
        );

        $cols = array(10, 60, 105, 140);

        $this->Line(10, $rows[0], 200, $rows[0]);

        $this->SetFont('Helvetica', '', 10);

        $this->writeXY($cols[0], $rows[1], 5, $this->i18n->translate('Address'));
        $this->writeXY($cols[1], $rows[1], 5, $this->i18n->translate('Identification'));
        $this->writeXY($cols[1], $rows[3], 5, $this->i18n->translate('VAT number'));
        $this->writeXY($cols[2], $rows[1], 5, $this->sellerAccount->getBankName());
        $this->writeXY($cols[3], $rows[1], 5, $this->i18n->translate('Phone'));
        $this->writeXY($cols[3], $rows[3], 5, $this->i18n->translate('E-mail'));

        $this->SetFont('Helvetica', '', 12);

        $this->writeXY($cols[0], $rows[2], 5, $this->sellerAddress);
        $this->writeXY($cols[1], $rows[2], 5, $this->sellerId);
        $this->writeXY($cols[1], $rows[4], 5, $this->sellerId);
        $this->writeXY($cols[2], $rows[2], 5, $this->sellerAccount);
        $this->writeXY($cols[3], $rows[2], 5, $this->sellerPhone);
        $this->writeXY($cols[3], $rows[4], 5, $this->sellerMail);
    }
}
