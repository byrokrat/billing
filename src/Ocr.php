<?php

declare(strict_types=1);

namespace byrokrat\billing;

/**
 * Ocr value object
 */
class Ocr
{
    use OcrHelper;

    /**
     * @var string Internal representation
     */
    private $ocr = '';

    /**
     * Construct value object
     *
     * @throws RuntimeException If ocr is not valid, including check and length digits
     */
    public function __construct(string $ocr)
    {
        if (!ctype_digit($ocr) || strlen($ocr) > 25 || strlen($ocr) < 2) {
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
     */
    public function getOcr(): string
    {
        return $this->ocr;
    }

    /**
     * Get ocr as string
     */
    public function __toString(): string
    {
        return $this->getOcr();
    }
}
