<?php

namespace LBHurtado\Missive\Handlers;

use LBHurtado\Missive\MissiveFacade as Missive;
use LBHurtado\Missive\Repositories\SMSRepository;
use LBHurtado\Tactician\Contracts\CommandInterface;
use LBHurtado\Tactician\Contracts\HandlerInterface;

class CreateSMSHandler implements HandlerInterface
{
    protected $smss;

    public function __construct(SMSRepository $smss)
    {
        $this->smss = $smss;
    }

    public function handle(CommandInterface $command)
    {
        tap($xxx = $this->smss->create([
            'from' => $command->from,
            'to' => $command->to,
            'message' => $command->message,
        ]), function ($sms) use ($xxx) {
            Missive::setSMS($sms);
        });
    }
}
