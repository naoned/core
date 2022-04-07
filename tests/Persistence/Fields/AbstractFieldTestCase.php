<?php

namespace Onyx\Persistence\Fields;

use Onyx\Persistence\Field;
use Onyx\Persistence\Exceptions\InvalidDataException;

abstract class AbstractFieldTestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider providerTestConvert
     */
    public function testConvert(Field $field, $value, $expected)
    {
        $convertedValue = $field->convert($value);
        $this->assertSame($expected, $convertedValue);
    }

    /**
     * @dataProvider providerTestConvertWithExceptions
     */
    public function testConvertWithExceptions(Field $field, $value)
    {
        $this->expectException(InvalidDataException::class);
        $field->convert($value);
    }

    abstract public function providerTestConvert();

    abstract public function providerTestConvertWithExceptions();
}
