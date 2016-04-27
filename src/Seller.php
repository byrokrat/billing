<?php

declare(strict_types=1);

namespace byrokrat\billing;

/**
 * Basic seller interface
 */
interface Seller
{
    /**
     * Get name of seller
     */
    public function getName(): string;
}
