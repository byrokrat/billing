<?php

namespace byrokrat\billing;

/**
 * Ocr value object
 */
class Ocr
{
    use OcrHelperTrait;

    /**
     * @var string Internal representation
     */
    private $ocr = '';

    /**
     * Construct value object
     *
     * @param  string $ocr
     * @throws RuntimeException If ocr is not valid, including check and length digits
     */
    public function __construct($ocr)
    {
        if (!is_string($ocr) || !ctype_digit($ocr) || strlen($ocr) > 25 || strlen($ocr) < 2) {
            throw new RuntimeException("Number must be numeric and contain between 2 and 25 digits");
        }

        if (substr($ocr, -2, 1) != $this->calculateLengthDigit(substr($ocr, 0, -2))) {
            throw new RuntimeException("Invalid length digit");
        }

        if (!$this->getLuhnCalculator()->isValid($ocr)) {
            throw new RuntimeException("Invalid check digit");
        }

        $this->ocr = $ocr;
    }

    /**
     * Get ocr as string
     *
     * @return string
     */
    public function getOcr()
    {
        return $this->ocr;
    }

    /**
     * Get ocr as string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getOcr();
    }
}
