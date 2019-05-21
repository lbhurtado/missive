<?php

namespace LBHurtado\Missive\Handlers;

use LBHurtado\Missive\Facades\Missive;
use LBHurtado\Tactician\Contracts\CommandInterface;
use LBHurtado\Tactician\Contracts\HandlerInterface;

class CreateSMSHandler implements HandlerInterface
{
    public function handle(CommandInterface $command)
    {
        Missive::createSMS($command->getProperties());
    }
}
