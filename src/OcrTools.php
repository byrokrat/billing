<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\checkdigit\Luhn;

/**
 * Tools for constructing and validating ocr numbers
 */
class OcrTools
{
    /**
     * @var Luhn Checksum calculator
     */
    private $luhn;

    public function __construct(Luhn $luhn = null)
    {
        $this->luhn = $luhn ?: new Luhn;
    }

    /**
     * Validate OCR number
     *
     * @throws Exception If ocr is not valid, including check and length digits
     */
    public function validateOcr(string $ocr): string
    {
        if (!ctype_digit($ocr) || strlen($ocr) > 25 || strlen($ocr) < 2) {
            throw new Exception("Number must be numeric and contain between 2 and 25 digits");
        }

        if (substr($ocr, -2, 1) != $this->calculateLengthDigit(substr($ocr, 0, -2))) {
            throw new Exception("Invalid length digit");
        }

        if (!$this->luhn->isValid($ocr)) {
            throw new Exception("Invalid check digit");
        }

        return $ocr;
    }

    /**
     * Create ocr from number by appending check and length digits
     *
     * @throws Exception If number is non-numeric or longer than 23 characters
     */
    public function createOcr(string $number): string
    {
        if (!ctype_digit($number) || strlen($number) > 23) {
            throw new Exception("Number must be numeric and contain a maximum of 23 digits");
        }

        $number .= $this->calculateLengthDigit($number);
        $number .= $this->luhn->calculateCheckDigit($number);

        return $number;
    }

    /**
     * Calculate length digit for raw number
     *
     * The length of $number plus 2 is used, to take length and check digits
     * into account.
     */
    private function calculateLengthDigit(string $number): string
    {
        return (string)((strlen($number) + 2) % 10);
    }
}
