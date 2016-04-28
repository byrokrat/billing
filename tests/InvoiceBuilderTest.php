<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\amount\Amount;

class InvoiceBuilderTest extends BaseTestCase
{
    private $builder;

    protected function setup()
    {
        $this->builder = (new InvoiceBuilder)
            ->setSerial('1')
            ->setSeller($this->getMock('byrokrat\billing\Seller'))
            ->setBuyer($this->getMock('byrokrat\billing\Buyer'));
    }

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

    public function testBuildInvoice()
    {
        $ocr = '232';
        $item = new StandardItem('', 0, new Amount('0'));
        $date = new \DateTime();
        $deduction = new Amount('100');

        $invoice = $this->builder
            ->setMessage('message')
            ->setOcr($ocr)
            ->addItem($item)
            ->setBillDate($date)
            ->setExpiresAfter(1)
            ->setDeduction($deduction)
            ->setCurrency('EUR')
            ->buildInvoice();

        $this->assertSame('message', $invoice->getMessage());
        $this->assertSame($ocr, $invoice->getOcr());
        $this->assertEquals([new ItemEnvelope($item)], $invoice->getItems());
        $this->assertSame($date, $invoice->getBillDate());
        $this->assertSame($deduction, $invoice->getDeduction());
        $this->assertSame('EUR', $invoice->getCurrency());
    }

    public function testGnerateWithoutBillDate()
    {
        $this->assertInstanceOf(
            'DateTime',
            $this->builder->buildInvoice()->getBillDate()
        );
    }

    public function testGeneratingWithoutOcr()
    {
        $this->assertEmpty(
            $this->builder->buildInvoice()->getOcr()
        );
    }

    public function testGenerateOcr()
    {
        $this->assertNotEmpty(
            $this->builder->generateOcr()->buildInvoice()->getOcr()
        );
    }
}
