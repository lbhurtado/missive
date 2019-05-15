<?php

namespace LBHurtado\Missive\Events;

use Opis\Events\Event;
use LBHurtado\Missive\Classes\SMSAbstract;

class SMSEvent extends Event
{
    protected $sms;

    public function setSMS(SMSAbstract $sms)
    {
        $this->sms = $sms;

        return $this;
    }

    public function getSMS()
    {
        return $this->sms;
    }
}
