<?php

namespace LBHurtado\Missive\Responders;

use League\Tactician\Middleware;
use LBHurtado\Missive\Resources\CreateSMSResource;

class CreateSMSResponder implements Middleware
{
    public function execute($command, callable $next)
    {
        $next($command);

        return (new CreateSMSResource($command))
            ->response()
            ->setStatusCode(200)
            ;
    }
}
