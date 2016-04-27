<?php

declare(strict_types=1);

namespace byrokrat\billing;

class OcrTest extends \PHPUnit_Framework_TestCase
{
    public function testValidOcr()
    {
        $this->assertSame(
            '12345682',
            (string) new Ocr('12345682')
        );
    }

    public function invalidStructureProvider()
    {
        return [
            ['a'],
            ['1'],
            ['12345678901234567890123456']
        ];
    }

    /**
     * @dataProvider invalidStructureProvider
     */
    public function testExceptionOnInvalidStructure($ocr)
    {
        $this->setExpectedException('byrokrat\billing\RuntimeException');
        new Ocr($ocr);
    }

    public function testExceptionOnInvalidLengthDigit()
    {
        $this->setExpectedException('byrokrat\billing\RuntimeException');
        new Ocr('12345602');
    }

    public function testExceptionOnInvalidCheckDigit()
    {
        $this->setExpectedException('byrokrat\billing\RuntimeException');
        new Ocr('12345680');
    }
}
