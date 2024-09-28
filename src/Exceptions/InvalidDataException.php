<?php

namespace D4T\UnFxSdk\Exceptions;

use Exception;

class InvalidDataException extends Exception
{
    public function __construct(string $error)
    {
        parent::__construct($error);
    }
}
