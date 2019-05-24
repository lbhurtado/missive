<?php

namespace LBHurtado\Missive\Actions\Middleware;

use League\Tactician\Middleware;
use LBHurtado\Missive\Facades\Missive;

class VerifyContactHandler implements Middleware
{

    public function execute($command, callable $next)
    {
        $next($command);

        Missive::verifyContact($command->message);
    }
}
