<?php

namespace LBHurtado\Missive\Handlers;

use LBHurtado\Missive\Missive;
use LBHurtado\Tactician\Contracts\CommandInterface;
use LBHurtado\Tactician\Contracts\HandlerInterface;

class CreateSMSHandler implements HandlerInterface
{
    protected $missive;

    public function __construct(Missive $missive)
    {
        $this->missive = $missive;
    }

    public function handle(CommandInterface $command)
    {
        $this->missive->create([
            'from' => $command->from,
            'to' => $command->to,
            'message' => $command->message,
        ]);
    }
}
