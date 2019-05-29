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
        $this->smss->create($this->getAttributes($command));
    }

    protected function getAttributes(CommandInterface $command)
    {
        $fields = array_values((new SMS)->getFillable());

        return optional(array_flip(config('missive.relay.providers')[config('missive.relay.default')]), function ($mapping) use ($command, $fields) {
            $attributes = [];
            foreach ($fields as $field) {
                $attributes[$field] = $command->getProperties()[$field];
            }

            return $attributes;
        });
    }
}
