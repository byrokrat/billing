<?php

declare(strict_types=1);

namespace byrokrat\billing;

/**
 * Create new ocr numbers
 */
class OcrFactory
{
    use OcrHelper;

    /**
     * Create new ocr from raw number
     *
     * Check and length digits are appended
     *
     * @throws RuntimeException If number is non-numeric or longer than 23 characters
     */
    public function createOcr(string $number): Ocr
    {
        if (!ctype_digit($number) || strlen($number) > 23) {
            throw new RuntimeException("Number must be numeric and contain a maximum of 23 digits");
        }

        $number .= $this->calculateLengthDigit($number);
        $number .= $this->getLuhnCalculator()->calculateCheckDigit($number);

        return new Ocr($number);
    }
}
