<?php

namespace SuperSimpleForms;

/**
 * Class Field
 * @package SuperSimpleForms
 */
class Field
{
    private $name;
    private $required;
    private $type = "text";
    private $label;
    private $default_value;
    private $validator;
    private $value;
    private $valid = false;

    /**
     * Field constructor.
     * @param string $name
     * @param string $type
     * @param bool $required
     * @param string $label
     * @param string|array $default_value
     * @param ValidatorInterface|null $validator
     */
    public function __construct(
        $name,
        $type = "text",
        $required = false,
        $label = "",
        $default_value = "",
        ValidatorInterface $validator = null
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->required = $required;
        $this->label = $label;
        $this->default_value = $default_value;
        $this->validator = $validator;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return array|string
     */
    public function getDefaultValue()
    {
        return $this->default_value;
    }

    /**
     * @return null|ValidatorInterface
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $value
     * @return bool True of valid or no validator false otherwise
     */
    public function setValue($value)
    {
        $this->value = $value;
        if (!is_null($this->validator)) {
            $this->valid = $this->validator->validate($this->value);
        } else {
            $this->valid = true;
        }
    }

    public function isValid()
    {
        return $this->valid;
    }

}
