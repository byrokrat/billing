<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\amount\Amount;

class ItemEnvelopeTest extends TestCase
{
    public function testGetDescription()
    {
        $this->assertEquals(
            'desc',
            (new ItemEnvelope($this->getBillableMock('desc')))->getBillingDescription()
        );
    }

    public function testGetCostPerUnit()
    {
        $this->assertTrue(
            (new ItemEnvelope($this->getBillableMock('', new Amount('100'))))->getCostPerUnit()->equals(
                new Amount('100')
            )
        );
    }

    public function testGetNrOfUnits()
    {
        $this->assertEquals(
            2,
            (new ItemEnvelope($this->getBillableMock('', null, 2)))->getNrOfUnits()
        );
    }

    public function testGetVatRate()
    {
        $this->assertEquals(
            .25,
            (new ItemEnvelope($this->getBillableMock('', null, 1, .25)))->getVatRate()
        );
    }

    public function testGetTotalUnitCost()
    {
        $this->assertTrue(
            (new ItemEnvelope($this->getBillableMock('', new Amount('100'), 2)))->getTotalUnitCost()->equals(
                new Amount('200')
            )
        );
    }

    public function testGetTotalVatCost()
    {
        $this->assertTrue(
            (new ItemEnvelope($this->getBillableMock('', new Amount('100'), 1, .25)))->getTotalVatCost()->equals(
                new Amount('25')
            )
        );
        $this->assertTrue(
            (new ItemEnvelope($this->getBillableMock('', new Amount('100'), 2, .125)))->getTotalVatCost()->equals(
                new Amount('25')
            )
        );
    }

    public function testGetTotalCost()
    {
        $this->assertTrue(
            (new ItemEnvelope($this->getBillableMock('', new Amount('100'), 2, .25)))->getTotalCost()->equals(
                new Amount('250')
            )
        );
    }
}
