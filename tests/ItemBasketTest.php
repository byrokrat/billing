<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\amount\Amount;
use byrokrat\amount\Currency;

class ItemBasketTest extends BaseTestCase
{
    public function plainAmountMethodsProvider()
    {
        return [
            ['getNrOfItems', 2],
            ['getNrOfUnits', 3],
            ['getTotalUnitCost', new Amount('300')],
            ['getTotalVatCost', new Amount('25')],
            ['getTotalCost', new Amount('325')],
        ];
    }

    /**
     * @dataProvider plainAmountMethodsProvider
     */
    public function testMethdsUsingPlainAmounts($method, $expected)
    {
        $this->assertEquals(
            $expected,
            (
                new ItemBasket(
                    new ItemEnvelope($this->getBillableMock('', new Amount('100'), 1, 25)),
                    new ItemEnvelope($this->getBillableMock('', new Amount('100'), 2, 0))
                )
            )->$method()
        );

    }

    public function currencyMethodsProvider()
    {
        return [
            ['getNrOfItems', 2],
            ['getNrOfUnits', 3],
            ['getTotalUnitCost', new Currency\SEK('300')],
            ['getTotalVatCost', new Currency\SEK('75')],
            ['getTotalCost', new Currency\SEK('375')],
        ];
    }

    /**
     * @dataProvider currencyMethodsProvider
     */
    public function testMethdsUsingCurrencies($method, $expected)
    {
        $this->assertEquals(
            $expected,
            (
                new ItemBasket(
                    new ItemEnvelope($this->getBillableMock('', new Currency\SEK('100'), 1, 25)),
                    new ItemEnvelope($this->getBillableMock('', new Currency\SEK('100'), 2, 25))
                )
            )->$method()
        );

    }

    public function testGetVatRates()
    {
        $this->assertCount(
            1,
            (
                new ItemBasket(
                    new ItemEnvelope($this->getBillableMock('', new Amount('100'), 1, 25)),
                    new ItemEnvelope($this->getBillableMock('', new Amount('100'), 1, 0))
                )
            )->getVatRates(),
            'Second item has VAT 0 and should not be included'
        );
    }

    public function testGetCurrencyVatRates()
    {
        $this->assertInstanceOf(
            Currency\SEK::CLASS,
            (
                new ItemBasket(
                    new ItemEnvelope($this->getBillableMock('', new Currency\SEK('100'), 1, 25)),
                    new ItemEnvelope($this->getBillableMock('', new Currency\SEK('100'), 1, 0))
                )
            )->getVatRates()[0]->getCostPerUnit()
        );
    }

    public function testExceptionOnInconsistentCurrencies()
    {
        $this->setExpectedException(Exception::CLASS);
        new ItemBasket(
            new ItemEnvelope($this->getBillableMock('', new Currency\SEK('100'))),
            new ItemEnvelope($this->getBillableMock('', new Currency\EUR('100')))
        );
    }

    public function testExceptioinOnUnknownCurrency()
    {
        $this->setExpectedException(Exception::CLASS);
        (new ItemBasket)->createCurrencyObject('0');
    }
}
