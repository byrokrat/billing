<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\amount\Amount;

class SimpleItemTest extends BaseTestCase
{
    public function testGetBillingDescription()
    {
        $this->assertEquals(
            'desc',
            (new SimpleItem('desc', new Amount('0')))->getBillingDescription()
        );
    }

    public function testGetCostPerUnit()
    {
        $this->assertEquals(
            new Amount('100'),
            (new SimpleItem('', new Amount('100')))->getCostPerUnit()
        );
    }

    public function testGetNrOfUnits()
    {
        $this->assertEquals(
            2,
            (new SimpleItem('', new Amount('0'), 2))->getNrOfUnits()
        );
    }

    public function testGetVatRate()
    {
        $this->assertEquals(
            new Amount('.25'),
            (new SimpleItem('', new Amount('0')))->getVatRate()
        );
    }
}
