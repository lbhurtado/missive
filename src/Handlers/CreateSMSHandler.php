<?php

namespace LBHurtado\Missive\Handlers;

use LBHurtado\Missive\Facades\Missive;
use LBHurtado\Missive\Repositories\SMSRepository;
use LBHurtado\Tactician\Contracts\CommandInterface;
use LBHurtado\Tactician\Contracts\HandlerInterface;

class CreateSMSHandler implements HandlerInterface
{
    /** @var SMSRepository  */
    protected $smss;

    /**
     * CreateSMSHandler constructor.
     * @param SMSRepository $smss
     */
    public function __construct(SMSRepository $smss)
    {
        $this->smss = $smss;
    }

    /**
     * Persist the missive in the database using
     * the repository create method and command attributes.
     *
     * @param CommandInterface $command
     */
    public function handle(CommandInterface $command)
    {
        $this->smss->create($this->getCommandAttributes($command));
    }

    /**
     * Arrange the attributes for creating the model.
     *
     * If the relay provider is telerivet:
     *
     *  from
     *
     *  [
     *      'secret' => 'ABC12344567890', //miscellaneous field
     *      'from_number' => '+639171234567',
     *      'to_number' => '+639187654321',
     *      'content' => 'The quick brown fox...',
     *  ]
     *
     *  to
     *
     *  [
     *      'from' => '+639171234567',
     *      'to' => '+639187654321',
     *      'message' => 'The quick brown fox...',
     *  ]
     *
     * @param CommandInterface $command
     * @return array
     */
    protected function getCommandAttributes(CommandInterface $command): array
    {
        return optional(Missive::getRelayProviderConfig(), function ($mapping) use ($command) {
            $attributes = [];
            $properties = $command->getProperties();
            foreach ($this->getSMSFields() as $field) {
                $property = $mapping[$field];
                $attributes[$field] = $properties[$property];
            }

            return $attributes;
        });
    }

    /**
     * Extract the fields of the SMS model e.g.
     *
     *  [
     *      'from',
     *      'to',
     *      'message',
     *  ]
     *
     * @return mixed
     */
    protected function getSMSFields()
    {
        return app($this->smss->model())->getFillable();
    }
}
