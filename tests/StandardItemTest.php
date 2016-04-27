<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\amount\Amount;

class StandardItemTest extends BaseTestCase
{
    private $item;

    protected function setup()
    {
        $this->item = new StandardItem('desc', 2, new Amount('100'), new Amount('.25'));
    }

    public function testGetDescription()
    {
        $this->assertEquals(
            'desc',
            $this->item->getDescription()
        );
    }

    public function testGetNrOfUnits()
    {
        $this->assertEquals(
            2,
            $this->item->getNrOfUnits()
        );
    }

    public function testGetCostPerUnit()
    {
        $this->assertEquals(
            new Amount('100'),
            $this->item->getCostPerUnit()
        );
    }

    public function testGetVatRate()
    {
        $this->assertEquals(
            new Amount('.25'),
            $this->item->getVatRate()
        );
    }
}
