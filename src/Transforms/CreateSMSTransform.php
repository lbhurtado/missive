<?php

namespace LBHurtado\Missive\Transforms;

use League\Tactician\Middleware;

class CreateSMSTransform implements Middleware
{
    public function execute($command, callable $next)
    {
    	$transformedProperties = $this->transform($command->getProperties());
    	$command->setPropertiesForValidation($transformedProperties);

        return $next($command);
    }

    public function transform(array $attributes)
    {
        return array_merge($attributes, [
            'from' => phone($attributes['from'], 'PH')->formatE164(),
            'to' => phone($attributes['to'], 'PH')->formatE164(),
            'message' => trim($attributes['message']),
        ]);
    }
}
