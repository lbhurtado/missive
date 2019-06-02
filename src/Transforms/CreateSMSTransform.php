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
        $transformer = [
            'from' => phone($attributes['from'], 'PH')->formatE164(),
            'to' => phone($attributes['to'], 'PH')->formatE164(),
            'message' => trim($attributes['message']),
        ];
        $mapped_transformer = [];
        $mapping = array_flip(Missive::getRelayProviderConfig());
        foreach ($mapping as $key => $value) {
        	$mapped_transformer[$key] = $transformer[$value];
        }

        return array_merge($attributes, $mapped_transformer);
    }
}
