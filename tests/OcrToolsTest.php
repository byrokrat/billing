<?php

declare(strict_types=1);

namespace byrokrat\billing;

class OcrToolsTest extends TestCase
{
    public function testCreateOcr()
    {
        $this->assertEquals(
            '12345682',
            (new OcrTools)->createOcr('123456')
        );
    }

    public function testExceptionOnCreateWithInvalidLength()
    {
        $this->expectException(Exception::CLASS);
        (new OcrTools)->createOcr('123456789012345678901234');
    }

    public function testExceptionOnCreateWithNonNumericValue()
    {
        $this->expectException(Exception::CLASS);
        (new OcrTools)->createOcr('123L');
    }

    public function testValidateOcr()
    {
        $this->assertSame(
            '12345682',
            (new OcrTools)->validateOcr('12345682')
        );
    }

    public function invalidOcrStructureProvider()
    {
        return [
            ['a'],
            ['1'],
            ['12345678901234567890123456']
        ];
    }

    /**
     * @dataProvider invalidOcrStructureProvider
     */
    public function testExceptionOnInvalidOcrStructure($ocr)
    {
        $this->expectException(Exception::CLASS);
        (new OcrTools)->validateOcr($ocr);
    }

    public function testExceptionOnInvalidOcrLengthDigit()
    {
        $this->expectException(Exception::CLASS);
        (new OcrTools)->validateOcr('12345602');
    }

    public function testExceptionOnInvalidOcrCheckDigit()
    {
        $this->expectException(Exception::CLASS);
        (new OcrTools)->validateOcr('12345680');
    }
}
