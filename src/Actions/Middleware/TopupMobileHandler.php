<?php

namespace LBHurtado\Missive\Actions\Middleware;

use Illuminate\Support\Arr;
use League\Tactician\Middleware;
use LBHurtado\Missive\Facades\Missive;

class TopupMobileHandler implements Middleware
{
    protected $regex = "/(?<country>(\+?63|0))?(?<mobile>[0-9]{10}) (?<amount>\d+)/";

    protected $mobile;

    protected $amount;

    public function execute($command, callable $next)
    {
        $next($command);

        Missive::topupMobile($this->process($command));
    }

    protected function process($command)
    {
        $matches = [];

        if (preg_match($this->regex, $command->message, $matches))
            Arr::set($matches, 'mobile', '+63'. $matches['mobile']);

        return $matches;
    }
}
