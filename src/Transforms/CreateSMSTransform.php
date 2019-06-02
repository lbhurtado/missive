<?php

namespace LBHurtado\Missive\Transforms;

use League\Tactician\Middleware;
use LBHurtado\Missive\Facades\Missive;

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
        $merged = array_merge($attributes, [
            'from' => phone($attributes['from'], 'PH')->formatE164(),
            'to' => phone($attributes['to'], 'PH')->formatE164(),
            'message' => trim($attributes['message']),
        ]);
        $mapping = array_flip(Missive::getRelayProviderConfig());
        $data = [];
        foreach ($mapping as $key => $value) {
        	$data[$key] = $merged[$value];
        }

        return $data;
    }
}
