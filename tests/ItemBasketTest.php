<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\amount\Amount;
use byrokrat\amount\Currency;

class ItemBasketTest extends TestCase
{
    public function testCount()
    {
        $basekt = new ItemBasket(
            new ItemEnvelope($this->getBillableMock('', new Amount('100'), 1, .25)),
            new ItemEnvelope($this->getBillableMock('', new Amount('100'), 2, 0))
        );

        $this->assertEquals(2, $basekt->getNrOfItems());
        $this->assertEquals(3, $basekt->getNrOfUnits());
    }

    public function plainAmountMethodsProvider()
    {
        return [
            ['getTotalUnitCost', new Amount('300')],
            ['getTotalVatCost', new Amount('25')],
            ['getTotalCost', new Amount('325')],
        ];
    }

    /**
     * @dataProvider plainAmountMethodsProvider
     */
    public function testMethdsUsingPlainAmounts(string $method, Amount $expected)
    {
        $this->assertTrue(
            (
                new ItemBasket(
                    new ItemEnvelope($this->getBillableMock('', new Amount('100'), 1, .25)),
                    new ItemEnvelope($this->getBillableMock('', new Amount('100'), 2, 0))
                )
            )->$method()->equals($expected)
        );
    }

    public function currencyMethodsProvider()
    {
        return [
            ['getTotalUnitCost', new Currency\SEK('300')],
            ['getTotalVatCost', new Currency\SEK('75')],
            ['getTotalCost', new Currency\SEK('375')],
        ];
    }

    /**
     * @dataProvider currencyMethodsProvider
     */
    public function testMethodsUsingCurrencies(string $method, Currency $expected)
    {
        $this->assertTrue(
            (
                new ItemBasket(
                    new ItemEnvelope($this->getBillableMock('', new Currency\SEK('100'), 1, .25)),
                    new ItemEnvelope($this->getBillableMock('', new Currency\SEK('100'), 2, .25))
                )
            )->$method()->equals($expected)
        );
    }

    public function testGetVatRates()
    {
        $rates = (
            new ItemBasket(
                new ItemEnvelope($this->getBillableMock('', new Amount('100'), 1, .25)),
                new ItemEnvelope($this->getBillableMock('', new Amount('100'), 1, .25)),
                new ItemEnvelope($this->getBillableMock('', new Amount('100'), 1, 0))
            )
        )->getVatRates();

        $this->assertCount(
            1,
            $rates,
            'Second item has VAT 0 and should not be included'
        );

        $this->assertTrue(
            $rates['0.25']['vat_total']->equals(
                new Amount('50')
            )
        );

        $this->assertTrue(
            $rates['0.25']['unit_total']->equals(
                new Amount('200')
            )
        );
    }

    public function testGetCurrencyVatRates()
    {
        $this->assertInstanceOf(
            Currency\SEK::CLASS,
            (
                new ItemBasket(
                    new ItemEnvelope($this->getBillableMock('', new Currency\SEK('100'), 1, .25)),
                    new ItemEnvelope($this->getBillableMock('', new Currency\SEK('100'), 1, 0))
                )
            )->getVatRates()['0.25']['vat_total']
        );
    }

    public function testExceptionOnInconsistentCurrencies()
    {
        $this->expectException(Exception::CLASS);
        new ItemBasket(
            new ItemEnvelope($this->getBillableMock('', new Currency\SEK('100'))),
            new ItemEnvelope($this->getBillableMock('', new Currency\EUR('100')))
        );
    }

    public function testExceptioinOnUnknownCurrency()
    {
        $this->expectException(Exception::CLASS);
        (new ItemBasket)->createCurrencyObject('0');
    }
}
