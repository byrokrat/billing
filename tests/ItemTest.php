<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\amount\Amount;

class ItemTest extends TestCase
{
    public function testGetBillingDescription()
    {
        $this->assertEquals(
            'desc',
            (new Item('desc', new Amount('0')))->getBillingDescription()
        );
    }

    public function testGetCostPerUnit()
    {
        $this->assertEquals(
            new Amount('100'),
            (new Item('', new Amount('100')))->getCostPerUnit()
        );
    }

    public function testGetNrOfUnits()
    {
        $this->assertEquals(
            2,
            (new Item('', new Amount('0'), 2))->getNrOfUnits()
        );
    }

    public function testGetVatRate()
    {
        $this->assertEquals(
            .25,
            (new Item('', new Amount('0')))->getVatRate()
        );
    }
}
