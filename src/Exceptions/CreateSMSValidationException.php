<?php

namespace LBHurtado\Missive\Exceptions;

use Exception;
use Illuminate\Validation\Validator;
use LBHurtado\Tactician\Contracts\CommandInterface;

class CreateSMSValidationException extends Exception
{
    public function __construct(CommandInterface $command, Validator $validator, $code = 0, Exception $previous = null) {
        parent::__construct('Validation Error!', $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
