<?php

namespace LBHurtado\Missive\Commands;

use LBHurtado\Tactician\Contracts\CommandInterface;

class CreateSMSCommand implements CommandInterface
{
    protected $properties;

    public function __construct(array $data)
    {
        $this->setPropertiesForValidation($data);
    }

    public function getProperties():array
    {
        return $this->properties;
    }

    protected function setPropertiesForValidation($data)
    {
        foreach ($this->properties = $data as $property => $value) {
            $this->{$property} = $value;
        }
    }
}
