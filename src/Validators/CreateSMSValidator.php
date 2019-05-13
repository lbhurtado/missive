<?php

namespace LBHurtado\Missive\Validators;

use Validator;
use League\Tactician\Middleware;
use LBHurtado\Missive\Exceptions\CreateSMSValidationException;

class CreateSMSValidator implements Middleware
{
    protected $rules = [
        'from'    => 'required',
        'to'      => 'required',
        'message' => 'string|max:500'
    ];

    public function execute($command, callable $next)
    {
        $validator = Validator::make((array) $command, $this->rules);

        if ($validator->fails()) {
            throw new CreateSMSValidationException($command, $validator);
        }

        return $next($command);
    }

}
