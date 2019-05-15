<?php

namespace LBHurtado\Missive\Events;

use Opis\Events\Event;
use LBHurtado\Missive\Classes\MobileHandle;

class RelayEvent extends Event
{
    protected $relay;

    public function setRelay(MobileHandle $relay)
    {
        $this->relay = $relay;

        return $this;
    }

    public function getRelay()
    {
        return $this->relay;
    }
}