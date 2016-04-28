<?php

declare(strict_types=1);

namespace byrokrat\billing;

class OcrToolsTest extends BaseTestCase
{
    public function testCreateOcr()
    {
        $this->assertEquals(
            '12345682',
            (new OcrTools)->create('123456')
        );
    }

    public function testExceptionOnCreateWithInvalidLength()
    {
        $this->setExpectedException(RuntimeException::CLASS);
        (new OcrTools)->create('123456789012345678901234');
    }

    public function testExceptionOnCreateWithNonNumericValue()
    {
        $this->setExpectedException(RuntimeException::CLASS);
        (new OcrTools)->create('123L');
    }

    public function testValidateOcr()
    {
        $this->assertTrue(
            (new OcrTools)->validate('12345682')
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
        $this->setExpectedException('byrokrat\billing\RuntimeException');
        (new OcrTools)->validate($ocr);
    }

    public function testExceptionOnInvalidOcrLengthDigit()
    {
        $this->setExpectedException('byrokrat\billing\RuntimeException');
        (new OcrTools)->validate('12345602');
    }

    public function testExceptionOnInvalidOcrCheckDigit()
    {
        $this->setExpectedException('byrokrat\billing\RuntimeException');
        (new OcrTools)->validate('12345680');
    }
}
