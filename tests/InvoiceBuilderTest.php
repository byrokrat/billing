<?php

namespace ledgr\billing;

use ledgr\amount\Amount;
use DateTime;

class InvoiceBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException ledgr\billing\Exception
     */
    public function testGetSerialException()
    {
        $builder = new InvoiceBuilder();
        $builder->getSerial();
    }

    /**
     * @expectedException ledgr\billing\Exception
     */
    public function testGetSellerException()
    {
        $builder = new InvoiceBuilder();
        $builder->getSeller();
    }

    /**
     * @expectedException ledgr\billing\Exception
     */
    public function testGetBuyerException()
    {
        $builder = new InvoiceBuilder();
        $builder->getBuyer();
    }

    public function testSetGetBillDate()
    {
        $builder = new InvoiceBuilder();
        $date = new DateTime();

        $builder->setBillDate($date);
        $this->assertSame($date, $builder->getBillDate());

        $builder->reset();

        $this->assertNotSame($date, $builder->getBillDate());
    }

    /**
     * @expectedException ledgr\billing\Exception
     */
    public function testGetOcrException()
    {
        $builder = new InvoiceBuilder();
        $builder->getOCR();
    }

    public function testSetGetGenerateOCR()
    {
        $builder = new InvoiceBuilder();
        $ocr = new OCR('232');

        $builder->setOCR($ocr);
        $this->assertSame($ocr, $builder->getOCR());

        $builder->reset()->generateOCR()->setSerial('1');

        $this->assertEquals(new OCR('133'), $builder->getOCR());
    }

    public function testGetInvoice()
    {
        $invoice = InvoiceBuilder::create()
            ->setSerial('1')
            ->generateOCR()
            ->setSeller(new LegalPerson('seller'))
            ->setBuyer(new LegalPerson('buyer'))
            ->setPaymentTerm(1)
            ->setDeduction(new Amount('100'))
            ->setMessage('message')
            ->setCurrency('EUR')
            ->addPost(new InvoicePost('', new Amount, new Amount))
            ->getInvoice();

        $this->assertEquals('message', $invoice->getMessage());
        $this->assertEquals('EUR', $invoice->getCurrency());
    }
}
