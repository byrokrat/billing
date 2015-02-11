<?php

namespace byrokrat\billing;

use byrokrat\amount\Amount;

class InvoicePostTest extends \PHPUnit_Framework_TestCase
{
    private $post;

    protected function setup()
    {
        $this->post = new InvoicePost('desc', new Amount('2'), new Amount('100'), new Amount('.25'));
    }

    public function testGetDescription()
    {
        $this->assertEquals(
            'desc',
            $this->post->getDescription()
        );
    }

    public function testGetNrOfUnits()
    {
        $this->assertEquals(
            new Amount('2'),
            $this->post->getNrOfUnits()
        );
    }

    public function testGetUnitCost()
    {
        $this->assertEquals(
            new Amount('100'),
            $this->post->getUnitCost()
        );
    }

    public function testGetUnitTotal()
    {
        $this->assertEquals(
            '200',
            $this->post->getUnitTotal()
        );
    }

    public function testGetVatRate()
    {
        $this->assertEquals(
            new Amount('.25'),
            $this->post->getVatRate()
        );
    }

    public function testGetVatTotal()
    {
        $this->assertEquals(
            '50',
            $this->post->getVatTotal()
        );
    }
}
