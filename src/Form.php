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
        $post_data = $request->getParsedBody();
        $file_data = $request->getUploadedFiles();

        foreach ($this->fields as $field) {
            $name = $field->getName();
            if ($this->isFile($field)) {
                if (isset($file_data[$name])) {
                    $field->setValue($file_data[$name]);
                }
                continue;
            }

            if (isset($post_data[$name])) {
                $field->setValue($post_data[$name]);
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

    /**
     * @param Field $form
     * @return bool
     */
    private function isFile(Field $form)
    {
        return $form->getType() === "file";
    }
}
