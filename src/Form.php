<?php

namespace SuperSimpleForms;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class SuperSimpleForms
 * @package SuperSimpleForms
 */
class Form
{
    /**
     * @var Field[]
     */
    private $fields;

    /**
     * SuperSimpleForms constructor.
     * @param Field[] $fields
     */
    public function __construct(array $fields)
    {
        foreach ($fields as $field) {
            if (!($field instanceof Field)) {
                throw new \InvalidArgumentException(sprintf(
                    "Fields must be of type SuperSimpleForms\Field. %s was given", gettype($field)
                ));
            }
        }
        $this->fields = $fields;
    }

    /**
     * @param ServerRequestInterface $request
     */
    public function handleRequest(ServerRequestInterface $request)
    {
        $postData = $request->getParsedBody();
        $fileData = $request->getUploadedFiles();

        foreach ($this->fields as $field) {
            $name = $field->getName();
            if ($this->isFile($field)) {
                if (isset($fileData[$name])) {
                    $field->setValue($fileData[$name]);
                }
                continue;
            }

            if (isset($postData[$name])) {
                $field->setValue($postData[$name]);
            }
        }
    }

    /**
     * @return Field[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    public function isValid()
    {
        $pass = true;
        foreach ($this->fields as $field) {
            $pass = $pass && $field->isValid();
        }
        return $pass;
    }

    public function getErrors()
    {
        $errors = [];
        foreach ($this->fields as $field) {
            $errors[$field->getName()] = $field->getValidator()->getErrors();
        }
    }

    /**
     * @param Field $form
     * @return bool
     */
    private function isFile(Field $form)
    {
        return $form->getType() === "file";
    }
}
