<?php

namespace LBHurtado\Missive\Handlers;

use LBHurtado\Missive\Models\SMS;
use LBHurtado\Missive\Repositories\SMSRepository;
use LBHurtado\Tactician\Contracts\CommandInterface;
use LBHurtado\Tactician\Contracts\HandlerInterface;

class CreateSMSHandler implements HandlerInterface
{
    /** @var SMSRepository  */
    protected $smss;

    public function __construct(SMSRepository $smss)
    {
        $this->smss = $smss;
    }

    public function handle(CommandInterface $command)
    {
        $fields = array_values((new SMS)->getFillable());
        $values = array_values($command->getProperties());
        $attributes = array_combine($fields, $values);

        $this->smss->create($attributes);
    }
}
