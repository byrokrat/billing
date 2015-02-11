<?php

namespace byrokrat\billing;

/**
 * Create new ocr numbers
 */
class OcrFactory
{
    use OcrHelperTrait;

    /**
     * Create new ocr from raw number
     *
     * Check and length digits are appended
     *
     * @param  string $number
     * @return Ocr
     * @throws RuntimeException If number is non-numeric or longer than 23 characters
     */
    public function createOcr($number)
    {
        if (!is_string($number) || !ctype_digit($number) || strlen($number) > 23) {
            throw new RuntimeException("Number must be numeric and contain a maximum of 23 digits");
        }

        $number .= $this->calculateLengthDigit($number);
        $number .= $this->getLuhnCalculator()->calculateCheckDigit($number);

        return new Ocr($number);
    }
}
