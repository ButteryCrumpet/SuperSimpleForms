<?php

use PHPUnit\Framework\TestCase;
use SuperSimpleForms\Field;
use SuperSimpleForms\ValidatorInterface;

/**
 * @testdox A Field
 */
class FieldTest extends TestCase
{
    public function testCanBeInstantiated()
    {
        $this->assertInstanceOf(
            Field::class,
            new Field("name")
        );
    }

    public function testSetsValueCorrectly()
    {
        $field = new Field("test");
        $field->setValue("value");
        $this->assertEquals("value", $field->getValue());
    }

    public function testValidatesCorrectly()
    {
        $mockValidator = $this->createMock(ValidatorInterface::class);
        $mockValidator
            ->method("validate");
        $mockValidator
            ->method("getErrors")
            ->willReturn("Some Error");

        $field = new Field(
            "valid-test",
            "text",
            false,
            "",
            "",
            $mockValidator
        );

        $field->setValue("t");
        $this->assertEquals("Some Error", $field->getValidator()->getErrors());
    }
}