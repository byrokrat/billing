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
        $this->assertEquals(
            new Amount('100'),
            (new ItemEnvelope($this->getBillableMock('', new Amount('100'))))->getCostPerUnit()
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
        $this->assertEquals(
            new Amount('200'),
            (new ItemEnvelope($this->getBillableMock('', new Amount('100'), 2)))->getTotalUnitCost()
        );
    }

    public function testGetTotalVatCost()
    {
        $this->assertEquals(
            new Amount('25'),
            (new ItemEnvelope($this->getBillableMock('', new Amount('100'), 1, .25)))->getTotalVatCost()
        );
        $this->assertEquals(
            new Amount('25'),
            (new ItemEnvelope($this->getBillableMock('', new Amount('100'), 2, .125)))->getTotalVatCost()
        );
    }

    public function testGetTotalCost()
    {
        $this->assertEquals(
            new Amount('250'),
            (new ItemEnvelope($this->getBillableMock('', new Amount('100'), 2, .25)))->getTotalCost()
        );
    }
}
