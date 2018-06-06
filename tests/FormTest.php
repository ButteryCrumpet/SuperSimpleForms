<?php

use PHPUnit\Framework\TestCase;
use SuperSimpleForms\Form;
use SuperSimpleForms\Field;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;

/**
 * @testdox A Form
 */
class FormTest extends TestCase
{
    public function testCanBeInstantiated()
    {
        $this->assertInstanceOf(
            Form::class,
            new Form([new Field("test")])
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testExceptionWhenNonFieldArrayIsPassed()
    {
        new Form(["hi"]);
    }

    public function testSetsValueCorrectly()
    {
        $form = new Form([new Field("test")]);
        $mockMessage = $this->createMock(ServerRequestInterface::class);
        $mockMessage
            ->method("getParsedBody")
            ->willReturn(["test" => "testValue"]);
        $mockMessage->method("getUploadedFiles")
            ->willReturn([]);

        $form->handleRequest($mockMessage);
        $field = $form->getFields()[0];

        $this->assertEquals("testValue", $field->getValue());
    }

    public function testSetsFileValueCorrectly()
    {
        $form = new Form([new Field("test-file", "file", false)]);
        $mockMessage = $this->createMock(ServerRequestInterface::class);
        $mockFileUpload = $this->createMock(UploadedFileInterface::class);
        $mockFileUpload
            ->method("getClientFileName")
            ->willReturn("testfile.txt");

        $mockMessage
            ->method("getParsedBody")
            ->willReturn([]);
        $mockMessage->method("getUploadedFiles")
            ->willReturn(["test-file" => $mockFileUpload]);

        $form->handleRequest($mockMessage);
        $field = $form->getFields()[0];

        $this->assertEquals("testfile.txt", $field->getValue()->getClientFileName());
    }
}

