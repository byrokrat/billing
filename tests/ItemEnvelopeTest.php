<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\amount\Amount;

class ItemEnvelopeTest extends BaseTestCase
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
            new Amount('.25'),
            (new ItemEnvelope($this->getBillableMock('', null, 1, new Amount('.25'))))->getVatRate()
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
            (new ItemEnvelope($this->getBillableMock('', new Amount('100'), 1, new Amount('.25'))))->getTotalVatCost()
        );
        $this->assertEquals(
            new Amount('50'),
            (new ItemEnvelope($this->getBillableMock('', new Amount('100'), 2, new Amount('.25'))))->getTotalVatCost()
        );
    }

    public function testGetTotalCost()
    {
        $this->assertEquals(
            new Amount('250'),
            (new ItemEnvelope($this->getBillableMock('', new Amount('100'), 2, new Amount('.25'))))->getTotalCost()
        );
    }
}
