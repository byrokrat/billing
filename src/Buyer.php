<?php

declare(strict_types=1);

namespace byrokrat\billing;

/**
 * Basic buyer interface
 */
interface Buyer
{
    /**
     * Get name of buyer
     */
    public function getName(): string;
}
