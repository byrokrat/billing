<?php

declare(strict_types=1);

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
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get name of actor
     */
    public function getName(): string
    {
        return $this->name;
    }
}
