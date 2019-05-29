<?php

namespace LBHurtado\Missive\Validators;

use Validator;
use League\Tactician\Middleware;
use LBHurtado\Missive\Exceptions\CreateSMSValidationException;

class CreateSMSValidator implements Middleware
{
    public function execute($command, callable $next)
    {
        $validator = Validator::make((array) $command, $this->getChecks());

        if ($validator->fails()) {
            throw new CreateSMSValidationException($command, $validator);
        }

        return $next($command);
    }

    protected function getChecks()
    {
        return optional(config('missive.relay.providers')[config('missive.relay.default')], function ($mapping) {
            $va = config('tactician.fields');
            $ar = array_flip($mapping);
            foreach ($ar as $key=>$value) {
                $ar[$key] = $va[$value];
            }
            return $ar;
        });
    }

}
