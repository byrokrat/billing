<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\amount\Amount;

class ItemBasketTest extends BaseTestCase
{
    public function testGerNrOfItems()
    {
        $this->assertSame(
            2,
            (
                new ItemBasket(
                    new ItemEnvelope($this->getBillableMock()),
                    new ItemEnvelope($this->getBillableMock())
                )
            )->getNrOfItems()
        );
    }

    public function testGerNrOfUnits()
    {
        $this->assertSame(
            3,
            (
                new ItemBasket(
                    new ItemEnvelope($this->getBillableMock('', null, 1)),
                    new ItemEnvelope($this->getBillableMock('', null, 2))
                )
            )->getNrOfUnits()
        );
    }

    public function testGetTotalUnitCost()
    {
        $this->assertEquals(
            new Amount('300'),
            (
                new ItemBasket(
                    new ItemEnvelope($this->getBillableMock('', new Amount('100'), 1)),
                    new ItemEnvelope($this->getBillableMock('', new Amount('100'), 2))
                )
            )->getTotalUnitCost()
        );
    }

    public function testGetTotalVatCost()
    {
        $this->assertEquals(
            new Amount('50'),
            (
                new ItemBasket(
                    new ItemEnvelope($this->getBillableMock('', new Amount('100'), 1)),
                    new ItemEnvelope($this->getBillableMock('', new Amount('100'), 1))
                )
            )->getTotalVatCost()
        );
    }

    public function testGetTotalCost()
    {
        $this->assertEquals(
            new Amount('250'),
            (
                new ItemBasket(
                    new ItemEnvelope($this->getBillableMock('', new Amount('100'), 1)),
                    new ItemEnvelope($this->getBillableMock('', new Amount('100'), 1))
                )
            )->getTotalCost()
        );
    }

    public function testGetVatRates()
    {
        $this->assertCount(
            1,
            (
                new ItemBasket(
                    new ItemEnvelope($this->getBillableMock('', new Amount('100'), 1, new Amount('.25'))),
                    new ItemEnvelope($this->getBillableMock('', new Amount('100'), 1, new Amount('0')))
                )
            )->getVatRates(),
            'Second item has VAT 0 and should not be included'
        );
    }
}
