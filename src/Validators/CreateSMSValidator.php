<?php

namespace LBHurtado\Missive\Validators;

use Validator;
use League\Tactician\Middleware;
use LBHurtado\Missive\Exceptions\CreateSMSValidationException;

class CreateSMSValidator implements Middleware
{
    public function execute($command, callable $next)
    {
        $validator = Validator::make((array) $command, config('tactician.fields'));

        if ($validator->fails()) {
            throw new CreateSMSValidationException($command, $validator);
        }

        return $next($command);
    }

}
