<?php

namespace byrokrat\billing;

/**
 * Basic buyer interface
 */
interface Buyer
{
    /**
     * Get name of buyer
     *
     * @return string
     */
    public function getName();
}
