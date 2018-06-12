<?php

namespace SuperSimpleForms;

interface ValidatorInterface
{
    public function validate($data);

    public function isValid();

    public function getErrors();
}
