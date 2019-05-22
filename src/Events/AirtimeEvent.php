<?php

namespace LBHurtado\Missive\Events;

use Opis\Events\Event;
use LBHurtado\Missive\Models\Airtime;

class AirtimeEvent extends Event
{
    protected $airtime;

    public function setAirtime(Airtime $airtime)
    {
        $this->airtime = $airtime;

        return $this;
    }

    public function getAirtime()
    {
        return $this->airtime;
    }
}