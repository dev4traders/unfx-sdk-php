<?php

namespace D4T\UnFxSdk\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('The resource you are looking for could not be found.');
    }
}
