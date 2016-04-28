<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\amount\Amount;
use byrokrat\amount\Currency;

class InvoiceTest extends BaseTestCase
{
    private function getInvoice()
    {
        return new Invoice(
            '1',
            $this->getMock(Seller::CLASS),
            $this->getMock(Buyer::CLASS),
            'message',
            '133',
            $this->getMock(ItemBasket::CLASS),
            new \DateTime('2014-01-01'),
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
                    $this->getMock(Seller::CLASS),
                    $this->getMock(Buyer::CLASS),
                    '',
                    '',
                    new ItemBasket(
                        new ItemEnvelope(
                            $this->getBillableMock('', new Amount('100'), 1, 25)
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
                    $this->getMock(Seller::CLASS),
                    $this->getMock(Buyer::CLASS),
                    '',
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
        $this->setExpectedException('byrokrat\amount\InvalidArgumentException');
        (
            new Invoice(
                '',
                $this->getMock(Seller::CLASS),
                $this->getMock(Buyer::CLASS),
                '',
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
            Seller::CLASS,
            $this->getInvoice()->getSeller()
        );
    }

    public function testGetBuyer()
    {
        $this->assertInstanceOf(
            Buyer::CLASS,
            $this->getInvoice()->getBuyer()
        );
    }

    public function testGetBillDate()
    {
        $this->assertEquals(new \DateTime('2014-01-01'), $this->getInvoice()->getBillDate());
    }

    public function testGetOcr()
    {
        $this->assertEquals('133', $this->getInvoice()->getOcr());
    }

    public function testGetMessage()
    {
        $this->assertEquals('message', $this->getInvoice()->getMessage());
    }

    public function testGetExpiresAfter()
    {
        $this->assertEquals(1, $this->getInvoice()->getExpiresAfter());
    }

    public function testGetExpirationDate()
    {
        $this->assertEquals(new \DateTime('2014-01-02'), $this->getInvoice()->getExpirationDate());
    }

    public function testGetDeduction()
    {
        $this->assertEquals(new Amount('100'), $this->getInvoice()->getDeduction());
    }
}
