<?php

namespace byrokrat\paperinvoice\I18n;

use byrokrat\paperinvoice\I18nInterface;
use byrokrat\amount\Amount;

/**
 * English internationalization
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class English implements I18nInterface
{
    public static $translations = array(
        'Amount'         => '#',
        'Unit cost'      => 'Price',
        'Total VAT'      => 'Total moms',
        'Tax registered' => '',
        'Identification' => 'Corporate id.',
        'VAT rate'       => 'VAT'
    );

    private $currencyFormatter;

    public function __construct(\NumberFormatter $currencyFormatter = null)
    {
        if (!$currencyFormatter) {
            $currencyFormatter = new \NumberFormatter('en', \NumberFormatter::CURRENCY);
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
        return $datetime->format('d/m/Y');
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

    public function formatUnits(Amount $units)
    {
        return $units;
    }
}
