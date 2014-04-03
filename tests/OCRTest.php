<?php

namespace ledgr\billing;

class OCRTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $this->assertEquals('12345682', (string)OCR::create('123456'));
    }

    /**
     * @expectedException ledgr\billing\RuntimeException
     */
    public function testCreateInvalidLength()
    {
        OCR::create('123456789012345678901234');
    }

    /**
     * @expectedException ledgr\billing\RuntimeException
     */
    public function testCreateNotNumeric()
    {
        OCR::create('123L');
    }

    public function testSetGet()
    {
        $o = new OCR('12345682');
        $this->assertSame('12345682', $o->getOCR());
        $this->assertSame('12345682', (string)$o);

        $o = new OCR('12345682');
        $this->assertSame('12345682', $o->getOCR());
        $this->assertSame('12345682', (string)$o);
    }

    public function invalidStructuresProvider()
    {
        return array(
            array(123),
            array('a'),
            array('1'),
            array('12345678901234567890123456'),
        );
    }

    /**
     * @expectedException ledgr\billing\RuntimeException
     * @dataProvider invalidStructuresProvider
     */
    public function testSetInvalidStructure($ocr)
    {
        new OCR($ocr);
    }

    /**
     * @expectedException ledgr\billing\RuntimeException
     */
    public function testSetInvalidLengthDigit()
    {
        new OCR('12345602');
    }

    /**
     * @expectedException ledgr\billing\RuntimeException
     */
    public function testSetInvalidCheckDigit()
    {
        new OCR('12345680');
    }
}
