<?php

namespace byrokrat\paperinvoice;

use byrokrat\amount\Amount;

/**
 * Interface for internationalization
 */
interface I18nInterface
{
    /**
     * Translate string
     *
     * @param  string $string
     * @return string
     */
    public function translate($string);

    /**
     * Format DateTime object
     *
     * @param  \DateTimeInterface $datetime
     * @return string
     */
    public function formateDate(\DateTimeInterface $datetime);

    /**
     * Format monetary amount without currency symbol
     *
     * @param  Amount $amount
     * @return string
     */
    public function formatCurrency(Amount $amount);

    /**
     * Format monetary amount with currency symbol
     *
     * @param  Amount $amount
     * @param  string $currency ISO 4217 currency code
     * @return string
     */
    public function formatCurrencySymbol(Amount $amount, $currency);

    /**
     * Format fractoinal amount as percentage
     *
     * @param  string $amount
     * @return string
     */
    public function formatPercentage($amount);

    /**
     * Format unit count
     *
     * @param  string $units
     * @return string
     */
    public function formatUnits($units);
}
