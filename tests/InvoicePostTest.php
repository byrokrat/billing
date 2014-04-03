<?php

namespace ledgr\billing;

use ledgr\amount\Amount;

class InvoicePostTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDescription()
    {
        $p = new InvoicePost('desc', new Amount('2'), new Amount('100'), new Amount('.25'));
        $this->assertEquals('desc', $p->getDescription());
    }

    public function testGetNrOfUnits()
    {
        $p = new InvoicePost('desc', new Amount('2'), new Amount('100'), new Amount('.25'));
        $this->assertEquals(new Amount('2'), $p->getNrOfUnits());
    }

    public function testGetUnitCost()
    {
        $p = new InvoicePost('desc', new Amount('2'), new Amount('100'), new Amount('.25'));
        $this->assertEquals(new Amount('100'), $p->getUnitCost());
    }

    public function testGetUnitTotal()
    {
        $p = new InvoicePost('desc', new Amount('2'), new Amount('100'), new Amount('.25'));
        $this->assertEquals('200', (string)$p->getUnitTotal());
    }

    public function testGetVatRate()
    {
        $p = new InvoicePost('desc', new Amount('2'), new Amount('100'), new Amount('.25'));
        $this->assertEquals(new Amount('.25'), $p->getVatRate());
    }

    public function testGetVatTotal()
    {
        $p = new InvoicePost('desc', new Amount('2'), new Amount('100'), new Amount('.25'));
        $this->assertEquals('50', (string)$p->getVatTotal());
    }
}
