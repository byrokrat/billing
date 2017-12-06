<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\amount\Amount;
use byrokrat\amount\Currency;

class InvoiceTest extends TestCase
{
    private function getInvoice()
    {
        return new Invoice(
            '1',
            $this->createMock(AgentInterface::CLASS),
            $this->createMock(AgentInterface::CLASS),
            '133',
            $this->createMock(ItemBasket::CLASS),
            new \DateTimeImmutable('2014-01-01'),
            1,
            new Amount('100')
        );
    }

    public function testGetInvoiceTotal()
    {
        $this->assertEquals(
            new Amount('125'),
            (
                new Invoice(
                    '',
                    $this->createMock(AgentInterface::CLASS),
                    $this->createMock(AgentInterface::CLASS),
                    '',
                    new ItemBasket(
                        new ItemEnvelope(
                            $this->getBillableMock('', new Amount('100'), 1, .25)
                        ),
                        new ItemEnvelope(
                            $this->getBillableMock('', new Amount('50'), 2, 0)
                        )
                    ),
                    null,
                    0,
                    new Amount('100')
                )
            )->getInvoiceTotal(),
            '1 unit á 100 and 25% VAT plus 2 units á 50 minus 100 in deduction should equal 125'
        );
    }

    public function testDeductionUsingDefaultCurrency()
    {
        $this->assertEquals(
            new Currency\SEK('125'),
            (
                new Invoice(
                    '',
                    $this->createMock(AgentInterface::CLASS),
                    $this->createMock(AgentInterface::CLASS),
                    '',
                    new ItemBasket(
                        new ItemEnvelope(
                            $this->getBillableMock('', new Currency\SEK('100'))
                        )
                    )
                )
            )->getInvoiceTotal(),
            'Deduction of 0 SEK should work as expected'
        );
    }

    public function testExceptionUsingInvalidDeductionCurrency()
    {
        $this->expectException('byrokrat\amount\InvalidArgumentException');
        (
            new Invoice(
                '',
                $this->createMock(AgentInterface::CLASS),
                $this->createMock(AgentInterface::CLASS),
                '',
                new ItemBasket(
                    new ItemEnvelope(
                        $this->getBillableMock('', new Currency\SEK('100'))
                    )
                ),
                null,
                0,
                new Currency\EUR('100')
            )
        )->getInvoiceTotal();
    }

    public function testGetSerial()
    {
        $this->assertEquals('1', $this->getInvoice()->getSerial());
    }

    public function testGetSeller()
    {
        $this->assertInstanceOf(
            AgentInterface::CLASS,
            $this->getInvoice()->getSeller()
        );
    }

    public function testGetBuyer()
    {
        $this->assertInstanceOf(
            AgentInterface::CLASS,
            $this->getInvoice()->getBuyer()
        );
    }

    public function testDates()
    {
        $this->assertEquals(
            new \DateTimeImmutable('2014-01-01'),
            $this->getInvoice()->getBillDate(),
            'Bill date should be set to 2014-01-01'
        );

        $this->assertEquals(
            1,
            $this->getInvoice()->getExpiresAfter(),
            'Expires after should be set to one day'
        );

        $this->assertEquals(
            new \DateTimeImmutable('2014-01-02'),
            $this->getInvoice()->getExpirationDate(),
            'Expiration date should then be one day after bill date'
        );

        $this->assertEquals(
            new \DateTimeImmutable('2014-01-01'),
            $this->getInvoice()->getBillDate(),
            'Calculating expiration date should not affect bill date'
        );
    }

    public function testGetOcr()
    {
        $this->assertEquals('133', $this->getInvoice()->getOcr());
    }

    public function testGetDeduction()
    {
        $this->assertEquals(new Amount('100'), $this->getInvoice()->getDeduction());
    }

    public function testAttributes()
    {
        $invoice = $this->getInvoice();

        $invoice->setAttribute('message', 'foo');
        $this->assertSame('foo', $invoice->getAttribute('message'));

        $this->assertSame('bar', $invoice->getAttribute('this-is-not-set', 'bar'));

        $this->assertSame(
            ['message' => 'foo'],
            $invoice->getAttributes()
        );

        $invoice->clearAttributes();

        $this->assertEmpty($invoice->getAttributes());
    }
}
