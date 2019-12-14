<?php

namespace byrokrat\paperinvoice\I18n;

use byrokrat\paperinvoice\I18nInterface;
use byrokrat\amount\Amount;

/**
 * Swedish internationalization
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class Swedish implements I18nInterface
{
    public static $translations = array(
        'INVOICE'        => 'FAKTURA',
        'Page '          => 'Sida ',
        ' of '             => ' av ',
        'Invoice date'   => 'Fakturadatum',
        'Invoice number' => 'Fakturanummer',
        'Reference'      => 'OCR-nummer',
        'Payment term'   => 'Betalningsvillkor',
        ' days'          => ' dagar',
        'Expiry date'    => 'Förfallodag',
        'VAT rate'       => 'Momssats',
        'Amount'         => 'Antal',
        'Unit cost'      => 'Á pris',
        'Total'          => 'Summa',
        'Basis'          => 'Underlag',
        'Total ex. VAT'  => 'Summa ex. moms',
        'Total VAT'      => 'Total moms',
        'Deduction'      => 'Erlagd förskottsbetalning',
        'Tax registered' => 'Godkänd för F-skatt.',
        'To pay'         => 'Att betala',
        'Address'        => 'Svarsadress',
        'Identification' => 'Organisationsnr',
        'VAT number'     => 'Momsreg.nr.',
        'Phone'          => 'Telefon',
        'E-mail'         => 'E-post',
        'Continues on next page..' => 'Sammanställningen fortsätter på nästa sida..'
    );

    private $currencyFormatter;

    public function __construct(\NumberFormatter $currencyFormatter = null)
    {
        if (!$currencyFormatter) {
            $currencyFormatter = new \NumberFormatter('sv', \NumberFormatter::CURRENCY);
            $currencyFormatter->setSymbol(\NumberFormatter::CURRENCY_SYMBOL, '');
            $currencyFormatter->setSymbol(\NumberFormatter::INTL_CURRENCY_SYMBOL, '');
            $currencyFormatter->setTextAttribute(\NumberFormatter::NEGATIVE_PREFIX, "-");
        }

        $this->currencyFormatter = $currencyFormatter;
    }

    public function translate($string)
    {
        if (array_key_exists($string, self::$translations)) {
            return self::$translations[$string];
        }

        return $string;
    }

    public function formateDate(\DateTimeInterface $datetime)
    {
        return $datetime->format('Y-m-d');
    }

    public function formatCurrency(Amount $amount)
    {
        return $this->currencyFormatter->format($amount->getFloat());
    }

    public function formatCurrencySymbol(Amount $amount, $currency)
    {
        return $this->currencyFormatter->formatCurrency($amount->getFloat(), $currency);
    }

    public function formatPercentage($amount)
    {
        return $amount;
    }

    public function formatUnits($units)
    {
        return $units;
    }
}
