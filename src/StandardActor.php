<?php

namespace byrokrat\billing;

use byrokrat\id\Id;
use byrokrat\id\NullId;
use byrokrat\banking\AccountNumber;

/**
 * Basic implementation of the Seller and Buyer interfaces
 */
class StandardActor implements Seller, Buyer
{
    /**
     * @var string Name of this actor
     */
    private $name;

    /**
     * Load name of actor
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Get name of actor
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
