<?php

namespace byrokrat\billing;

use byrokrat\amount\Amount;
use DateTime;

class InvoiceTest extends \PHPUnit_Framework_TestCase
{
    private function getInvoice()
    {
        return new Invoice(
            '1',
            new LegalPerson('seller'),
            new LegalPerson('buyer'),
            'message',
            new Ocr('133'),
            $this->getPosts(),
            new DateTime('2014-01-01'),
            1,
            new Amount('100'),
            'SEK'
        );
    }

    private function getPosts()
    {
        return [
            new InvoicePost(
                '',
                new Amount('1'),
                new Amount('100', 2),
                new Amount('.25', 2)
            ),
            new InvoicePost(
                '',
                new Amount('2'),
                new Amount('50', 2),
                new Amount('0', 2)
            )
        ];
    }

    public function testGetPosts()
    {
        $this->assertEquals($this->getPosts(), $this->getInvoice()->getPosts());
    }

    public function testGetVatTotal()
    {
        $this->assertEquals('25', (string)$this->getInvoice()->getVatTotal());
    }

    public function testGetUnitTotal()
    {
        $this->assertEquals('200', (string)$this->getInvoice()->getUnitTotal());
    }

    public function testGetInvoiceTotal()
    {
        $this->assertEquals('125', (string)$this->getInvoice()->getInvoiceTotal());
    }

    public function testGetVatTotals()
    {
        // Second post has VAT 0 and should not be included
        $rates = $this->getPosts();
        array_pop($rates);

        $this->assertEquals($rates, $this->getInvoice()->getVatTotals());
    }

    public function testGetSerial()
    {
        $this->assertEquals('1', $this->getInvoice()->getSerial());
    }

    public function testGetSeller()
    {
        $this->assertEquals('seller', $this->getInvoice()->getSeller()->getName());
    }

    public function testGetBuyer()
    {
        $this->assertEquals('buyer', $this->getInvoice()->getBuyer()->getName());
    }

    public function testGetBillDate()
    {
        $this->assertEquals(new DateTime('2014-01-01'), $this->getInvoice()->getBillDate());
    }

    public function testGetOcr()
    {
        $this->assertEquals(new Ocr('133'), $this->getInvoice()->getOcr());
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
        $this->assertEquals(new DateTime('2014-01-02'), $this->getInvoice()->getExpirationDate());
    }

    public function testGetDeduction()
    {
        $this->assertEquals(new Amount('100'), $this->getInvoice()->getDeduction());
    }

    public function testGetCurrency()
    {
        $this->assertEquals('SEK', $this->getInvoice()->getCurrency());
    }
}
