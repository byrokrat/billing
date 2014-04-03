<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\billing;

use ledgr\checkdigit\Modulo10;

/**
 * OCR number generation and validation
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class OCR
{
    /**
     * @var string Internal ocr representation
     */
    private $ocr = '';

    /**
     * OCR number generation and validation
     * 
     * OCR must have a valid check and length digits
     *
     * @param  string           $ocr
     * @throws RuntimeException If ocr is unvalid
     */
    public function __construct($ocr)
    {
        // Validate length
        if (!is_string($ocr)
            || !ctype_digit($ocr)
            || strlen($ocr) > 25
            || strlen($ocr) < 2
        ) {
            throw new RuntimeException("\$ocr must be numeric and contain between 2 and 25 digits");
        }

        $arOcr = str_split($ocr);
        $check = array_pop($arOcr);
        $length = array_pop($arOcr);
        $base = implode('', $arOcr);

        // Validate length digit
        if ($length != self::calcLengthDigit($base)) {
            throw new RuntimeException("Invalid length digit");
        }

        // Validate check digit
        if ($check != Modulo10::getCheckDigit($base . $length)) {
            throw new RuntimeException("Invalid check digit");
        }

        $this->ocr = $ocr;
    }

    /**
     * Get OCR as string
     *
     * @return string
     */
    public function getOCR()
    {
        return $this->ocr;
    }

    /**
     * Get OCR as string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getOCR();
    }

    /**
     * Create OCR from number
     * 
     * Check and length digits are appended
     *
     * @param  string           $nr
     * @return OCR
     * @throws RuntimeException If $nr is unvalid
     */
    public static function create($nr)
    {
        if (!is_string($nr) || !ctype_digit($nr) || strlen($nr) > 23) {
            throw new RuntimeException("\$nr must be numeric and contain a maximum of 23 digits");
        }

        // Calculate and append length digit
        $nr .= self::calcLengthDigit($nr);

        // Calculate and append check digit
        $nr .= Modulo10::getCheckDigit($nr);

        return new OCR($nr);
    }

    /**
     * Calculate length digit for string
     *
     * The length of $nr plus 2 is used, to take length and check digits into
     * account.
     *
     * @param  $nr
     * @return string
     */
    private static function calcLengthDigit($nr)
    {
        return (string)(strlen($nr) + 2) % 10;
    }
}
