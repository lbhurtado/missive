<?php

namespace LBHurtado\Missive\Actions\Middleware;

use League\Tactician\Middleware;
use LBHurtado\Missive\Facades\Missive;
use LBHurtado\Missive\Types\ChargeType;
use LBHurtado\Missive\Repositories\AirtimeRepository;

class ChargeSMSMiddleware implements Middleware
{
    protected $airtimes;

    public function __construct(AirtimeRepository $airtimes)
    {
        $this->airtimes = $airtimes;
    }

    public function execute($command, callable $next)
    {
        $next($command);

        Missive::chargeSMS(ChargeType::INCOMING_SMS());
    }
}
