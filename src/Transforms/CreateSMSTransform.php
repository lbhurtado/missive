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
        $mapping = Missive::getRelayProviderConfig();

        foreach ($mapping as $key => $value) {
            $$key = $value;
        }

        $transformer = [
            $from => phone($attributes[$from], 'PH')->formatE164(),
            $to => phone($attributes[$to], 'PH')->formatE164(),
            $message => trim($attributes[$message]),
        ];

        return $transformer;
    }
}
