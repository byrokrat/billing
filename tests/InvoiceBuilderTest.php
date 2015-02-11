<?php

namespace byrokrat\billing;

use byrokrat\amount\Amount;
use DateTime;

class InvoiceBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionWhenSerialNotSet()
    {
        $this->setExpectedException('byrokrat\billing\RuntimeException');
        (new InvoiceBuilder)->getSerial();
    }

    public function testExceptionWhenSellerNotSet()
    {
        $this->setExpectedException('byrokrat\billing\RuntimeException');
        (new InvoiceBuilder)->getSeller();
    }

    public function testExceptionWhenBuyerNotSet()
    {
        $this->setExpectedException('byrokrat\billing\RuntimeException');
        (new InvoiceBuilder)->getBuyer();
    }

    public function testExceptionWhenOcrNotSet()
    {
        $this->setExpectedException('byrokrat\billing\RuntimeException');
        (new InvoiceBuilder)->getOcr();
    }

    public function testBillDate()
    {
        $builder = new InvoiceBuilder();
        $date = new DateTime();

        $builder->setBillDate($date);
        $this->assertSame($date, $builder->getBillDate());

        $builder->reset();

        $this->assertNotSame($date, $builder->getBillDate());
    }

    public function testGenerateOcr()
    {
        $builder = new InvoiceBuilder();
        $ocr = new Ocr('232');

        $builder->setOcr($ocr);
        $this->assertSame($ocr, $builder->getOcr());

        $builder->reset()->setGenerateOcr()->setSerial('1');

        $this->assertEquals(new Ocr('133'), $builder->getOcr());
    }

    public function testGetInvoice()
    {
        $invoice = (new InvoiceBuilder)
            ->setSerial('1')
            ->setGenerateOcr()
            ->setSeller(new LegalPerson('seller'))
            ->setBuyer(new LegalPerson('buyer'))
            ->setExpiresAfter(1)
            ->setDeduction(new Amount('100'))
            ->setMessage('message')
            ->setCurrency('EUR')
            ->addPost(new InvoicePost('', new Amount('0'), new Amount('0')))
            ->getInvoice();

        $this->assertEquals('message', $invoice->getMessage());
        $this->assertEquals('EUR', $invoice->getCurrency());
    }
}
