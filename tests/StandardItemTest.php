<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\amount\Amount;

class StandardItemTest extends \PHPUnit_Framework_TestCase
{
    private $item;

    protected function setup()
    {
        $this->item = new StandardItem('desc', new Amount('2'), new Amount('100'), new Amount('.25'));
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
            new Amount('2'),
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

    public function testGetTotalUnitCost()
    {
        $this->assertEquals(
            new Amount('200'),
            $this->item->getTotalUnitCost()
        );
    }

    public function testGetVatRate()
    {
        $this->assertEquals(
            new Amount('.25'),
            $this->item->getVatRate()
        );
    }

    public function testGetTotalVatCost()
    {
        $this->assertEquals(
            new Amount('50'),
            $this->item->getTotalVatCost()
        );
    }

    public function testGetTotalCost()
    {
        $this->assertEquals(
            new Amount('250'),
            $this->item->getTotalCost()
        );
    }
}
