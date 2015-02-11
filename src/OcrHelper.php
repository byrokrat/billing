<?php

namespace byrokrat\billing;

use byrokrat\checkdigit\Luhn;

/**
 * Helpers to work with check- and length digits in ocr numbers
 */
trait OcrHelper
{
    /**
     * @var Luhn Checksum calculator
     */
    private $checksum;

    /**
     * Get checksum calculator
     *
     * @return Luhn
     */
    protected function getLuhnCalculator()
    {
        return $this->checksum = $this->checksum ?: new Luhn;
    }

    /**
     * Calculate length digit for raw number
     *
     * The length of $rawNr plus 2 is used, to take length and check digits into
     * account.
     *
     * @param  string $number
     * @return string
     */
    protected function calculateLengthDigit($number)
    {
        return (string)(strlen($number) + 2) % 10;
    }
}
