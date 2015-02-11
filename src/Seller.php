<?php

namespace byrokrat\billing;

/**
 * Basic seller interface
 */
interface Seller
{
    /**
     * Get name of seller
     *
     * @return string
     */
    public function getName();
}
